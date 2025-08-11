<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIInsightsService
{
    protected string $apiKey;
    protected string $apiUrl;

    public function __construct()
    {
        // $this->apiKey = config('services.gemini.key');
        $this->apiKey = env('HUGGING_FACE_API');
        // $this->apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent';
        $this->apiUrl = 'https://router.huggingface.co/v1/chat/completions';
    }

    public function generateInsights(string $type, string $dateRange): array
    {
        try {
            // Step 1: Gather relevant data
            $data = $this->gatherData($type, $dateRange);
            
            // Step 2: Format data for AI
            $prompt = $this->buildPrompt($type, $data);
            
            // Step 3: Send to Gemini API
            $insights = $this->callOpenAICompatible($prompt);
            
            // Step 4: Structure the response
            return $this->formatInsights($insights, $type);
        } catch (\Exception $e) {
            Log::error('AI Insights Generation Failed', [
                'type' => $type,
                'dateRange' => $dateRange,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    private function gatherData(string $type, string $dateRange): array
    {
        $days = $this->getDaysFromRange($dateRange);
        $startDate = now()->subDays($days);

        return match($type) {
            'revenue_analysis' => $this->getRevenueData($startDate),
            'service_performance' => $this->getServiceData($startDate),
            'customer_insights' => $this->getCustomerData($startDate),
            'forecast' => $this->getForecastData($startDate),
            default => $this->getRevenueData($startDate)
        };
    }

    private function getDaysFromRange(string $range): int
    {
        return match($range) {
            '7_days' => 7,
            '30_days' => 30,
            '90_days' => 90,
            '365_days' => 365,
            default => 30
        };
    }

    private function getRevenueData($startDate): array
    {
        return [
            'total_revenue' => DB::table('invoices')
                ->where('created_at', '>=', $startDate)
                ->sum('total_amount'),
            
            'invoice_count' => DB::table('invoices')
                ->where('created_at', '>=', $startDate)
                ->count(),
                
            'avg_invoice_amount' => DB::table('invoices')
                ->where('created_at', '>=', $startDate)
                ->avg('total_amount'),
            
            'daily_revenue' => DB::table('invoices')
                ->where('created_at', '>=', $startDate)
                ->selectRaw('DATE(created_at) as date, SUM(total_amount) as revenue, COUNT(*) as invoice_count')
                ->groupBy('date')
                ->orderBy('date')
                ->get(),
            
            // 'service_revenue' => DB::table('invoices')
            //     ->join('services', 'invoices.service_id', '=', 'services.id')
            //     ->where('invoices.created_at', '>=', $startDate)
            //     ->selectRaw('services.service_name, SUM(invoices.total_amount) as revenue, COUNT(*) as count, AVG(invoices.total_amount) as avg_invoice')
            //     ->groupBy('services.id', 'services.service_name')
            //     ->orderBy('revenue', 'desc')
            //     ->get(),
            
            'customer_revenue' => DB::table('invoices')
                ->join('users', 'invoices.customer_id', '=', 'users.id')
                ->where('invoices.created_at', '>=', $startDate)
                ->selectRaw('users.name, SUM(invoices.total_amount) as revenue, COUNT(*) as invoice_count')
                ->groupBy('users.id', 'users.name')
                ->orderBy('revenue', 'desc')
                ->take(15)
                ->get(),
                
            'previous_period_comparison' => [
                'current_period' => DB::table('invoices')
                    ->where('created_at', '>=', $startDate)
                    ->sum('total_amount'),
                'previous_period' => DB::table('invoices')
                    ->where('created_at', '>=', $startDate->copy()->subDays($this->getDaysFromRange('30_days')))
                    ->where('created_at', '<', $startDate)
                    ->sum('total_amount')
            ]
        ];
    }

    private function getServiceData($startDate): array
{
    return [
        'service_performance' => DB::table('invoices')
            ->join('invoice_items', 'invoices.id', '=', 'invoice_items.invoice_id')
            ->where('invoices.created_at', '>=', $startDate)
            ->selectRaw('
                invoice_items.description, 
                invoice_items.id,
                SUM(invoice_items.quantity * invoice_items.unit_price) as total_revenue,
                COUNT(DISTINCT invoices.id) as total_invoices,
                AVG(invoices.total_amount) as avg_invoice_amount,
                COUNT(DISTINCT invoices.customer_id) as unique_customers
            ')
            ->groupBy('invoice_items.id', 'invoice_items.description')
            ->orderBy('total_revenue', 'desc')
            ->get(),
        
        'service_trends' => DB::table('invoices')
            ->join('invoice_items', 'invoices.id', '=', 'invoice_items.invoice_id')
            ->where('invoices.created_at', '>=', $startDate)
            ->selectRaw('
                invoice_items.description,
                invoice_items.id,
                DATE_FORMAT(invoices.created_at, "%Y-%m") as month,
                SUM(invoice_items.quantity * invoice_items.unit_price) as revenue,
                COUNT(DISTINCT invoices.id) as invoice_count
            ')
            ->groupBy('invoice_items.id', 'invoice_items.description', 'month')
            ->orderBy('month', 'desc')
            ->get()
    ];
}

    private function getCustomerData($startDate): array
    {
        return [
            'top_customers' => DB::table('invoices')
                ->join('users', 'invoices.customer_id', '=', 'users.id')
                ->where('invoices.created_at', '>=', $startDate)
                ->selectRaw('
                    users.name,
                    SUM(invoices.total_amount) as total_spent,
                    COUNT(*) as total_invoices,
                    AVG(invoices.total_amount) as avg_invoice,
                    MAX(invoices.created_at) as last_invoice_date
                ')
                ->groupBy('users.id', 'users.name')
                ->orderBy('total_spent', 'desc')
                ->take(20)
                ->get(),
            
            'customer_segments' => DB::table('invoices')
                ->join('users', 'invoices.customer_id', '=', 'users.id')
                ->where('invoices.created_at', '>=', $startDate)
                ->selectRaw('
                    COUNT(DISTINCT users.id) as total_customers,
                    SUM(invoices.total_amount) as total_revenue,
                    AVG(invoices.total_amount) as avg_invoice_value
                ')
                ->first()
        ];
    }

    private function getForecastData($startDate): array
    {
        return [
            'historical_monthly' => DB::table('invoices')
                ->where('created_at', '>=', now()->subYear())
                ->selectRaw('
                    DATE_FORMAT(created_at, "%Y-%m") as month,
                    SUM(total_amount) as monthly_revenue,
                    COUNT(*) as monthly_invoices,
                    AVG(total_amount) as avg_invoice_amount
                ')
                ->groupBy('month')
                ->orderBy('month')
                ->get(),
            
            'weekly_trends' => DB::table('invoices')
                ->where('created_at', '>=', now()->subWeeks(12))
                ->selectRaw('
                    WEEK(created_at) as week,
                    YEAR(created_at) as year,
                    SUM(total_amount) as weekly_revenue,
                    COUNT(*) as weekly_invoices
                ')
                ->groupBy('year', 'week')
                ->orderBy('year')
                ->orderBy('week')
                ->get()
        ];
    }

    private function buildPrompt(string $type, array $data): string
    {
        $basePrompt = "You are an expert business intelligence analyst. Analyze the business data and provide insights in this exact JSON format:

```json
{
  \"summary\": \"2-3 sentence overview of key findings\",
  \"key_findings\": [
    \"Most important insight with specific numbers and percentages\",
    \"Second key finding with concrete data points\",
    \"Third significant discovery with actionable details\",
    \"Fourth notable pattern or trend\"
  ],
  \"recommendations\": [
    \"Specific actionable recommendation with expected impact\",
    \"Second priority action with implementation steps\",
    \"Third strategic suggestion with timeline\",
    \"Fourth optimization opportunity\"
  ],
  \"forecast_predictions\": [
    \"Realistic prediction with confidence level and timeframe\",
    \"Expected trend with supporting reasoning\",
    \"Risk factors and mitigation strategies\"
  ]
}
```";
  
$dataString = json_encode($data, JSON_PRETTY_PRINT);
    
    return match($type) {
        'revenue_analysis' => $basePrompt . "\n\n**REVENUE ANALYSIS DATA:**\n" . $dataString . 
            "\n\n**Focus:** Revenue trends, growth opportunities, seasonal patterns, customer spending behavior.",
        
        'service_performance' => $basePrompt . "\n\n**SERVICE PERFORMANCE DATA:**\n" . $dataString . 
            "\n\n**Focus:** Most profitable services, underperforming areas, pricing optimization, service portfolio recommendations.",
        
        'customer_insights' => $basePrompt . "\n\n**CUSTOMER ANALYSIS DATA:**\n" . $dataString . 
            "\n\n**Focus:** Customer value analysis, retention patterns, segmentation insights, acquisition opportunities.",
        
        'forecast' => $basePrompt . "\n\n**FORECASTING DATA:**\n" . $dataString . 
            "\n\n**Focus:** 3-month revenue forecast, growth predictions, seasonal adjustments, risk assessment.",
        
        default => $basePrompt . "\n\n**BUSINESS DATA:**\n" . $dataString
    };
}

private function callGemini(string $prompt): string
{
    
    $response = Http::timeout(30)->withHeaders([
        'Content-Type' => 'application/json',
    ])->post($this->apiUrl . '?key=' . $this->apiKey, [
        'contents' => [
            [
                'parts' => [
                    ['text' => $prompt]
                ]
            ]
        ],
        'generationConfig' => [
            'temperature' => 0.1,
            'topK' => 40,
            'topP' => 0.95,
            'maxOutputTokens' => 4096,
        ]
    ]);

    if ($response->successful()) {
        $result = $response->json();
        if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
            return $result['candidates'][0]['content']['parts'][0]['text'];
        }
    }

    throw new \Exception('Gemini API call failed: ' . $response->body());
}

private function callOpenAICompatible(string $prompt, bool $stream = false): string
{
    $response = Http::timeout(30)->withHeaders([
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . $this->apiKey,
    ])->post($this->apiUrl, [
        'messages' => [
            [
                'role' => 'user',
                'content' => $prompt
            ]
        ],
        'model' => 'openai/gpt-oss-120b:novita',
        'stream' => false,
        'temperature' => 0.1,
        'max_tokens' => 4096, 
        'top_p' => 0.95,
    ]);

    if ($response->successful()) {
        $result = $response->json();
        
        if (isset($result['choices'][0]['message']['content'])) {
            // dd($result['choices'][0]['message']['content']);
            return $result['choices'][0]['message']['content'];
        }
    }

    throw new \Exception('OpenAI-compatible API call failed: ' . $response->body());
}

private function formatInsights(string $rawInsights, string $type): array
{
    // Clean up the response
    $cleanedResponse = preg_replace('/<think>.*?<\/think>/s', '', $rawInsights);    
    $cleanedResponse = preg_replace('/```json\s*/', '', $cleanedResponse);
    $cleanedResponse = preg_replace('/```\s*$/', '', $cleanedResponse);
    $cleanedResponse = trim($cleanedResponse);
    
    $decoded = json_decode($cleanedResponse, true);
    
    if ($decoded && json_last_error() === JSON_ERROR_NONE) {
        return [
            'type' => $type,
            'generated_at' => now(),
            'summary' => $decoded['summary'] ?? 'Analysis completed successfully',
            'key_findings' => $decoded['key_findings'] ?? [],
            'recommendations' => $decoded['recommendations'] ?? [],
            'forecast_predictions' => $decoded['forecast_predictions'] ?? [],
            'confidence_score' => 90,
            'raw_response' => $rawInsights
        ];
    }
    
    return [
        'type' => $type,
        'generated_at' => now(),
        'summary' => 'AI analysis completed - see details below',
        'key_findings' => ['Analysis available in raw response'],
        'recommendations' => ['Please review raw response for recommendations'],
        'forecast_predictions' => ['Predictions available in raw response'],
        'confidence_score' => 70,
        'raw_response' => $rawInsights
    ];
}

}

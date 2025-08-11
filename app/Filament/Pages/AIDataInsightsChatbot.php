<?php


namespace App\Filament\Pages;

use App\Models\CustomerServices;
use App\Models\InvoiceItem;
use App\Models\invoices as Invoices;
use App\Models\User;
use App\Services\AIChatInshights;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class AIDataInsightsChatbot extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationLabel = 'AI Assistant';
    protected static string $view = 'filament.pages.a-i-data-insights-chatbot';

    public $question = '';
    public $response = '';
    public $loading = false;

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('AI Business Assistant')
                ->description('Ask questions about your business data and get AI-powered insights')
                ->schema([
                    Textarea::make('question')
                        ->label('Your Question')
                        ->placeholder('e.g., What niche market should I target specifically instead of covering all?')
                        ->rows(4)
                        ->required()
                        ->helperText('Ask about customers, sales, market opportunities, or any business insights'),
                ])
        ]);
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('ask')
                ->label('Ask AI')
                ->icon('heroicon-o-sparkles')
                ->action('askGemini')
                ->color('primary')
                ->size('lg')
                ->disabled($this->loading === true),
        ];
    }

    public function askGemini()
    {
        $this->validate([
            'question' => 'required|string|min:3'
        ]);

        try {
            $this->loading = true;
            $this->response = '';

            // Force a UI update to show loading state
            $this->dispatch('$refresh');

            // Get relevant data based on question context
            $contextData = $this->getRelevantData($this->question);

            $geminiService = app(AIChatInshights::class);
            $this->response = $geminiService->askWithContext($this->question, $contextData);
        } catch (\Exception $e) {
            \Log::error('Sorry, there was an error processing your request:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            Notification::make()
            ->title('Error')
            ->body('Sorry, there was an error processing your request')
            ->danger()
            ->send();

        } finally {
            $this->loading = false;
        }
    }

    private function getRelevantData(string $question): array
    {
        $data = [];
        $questionLower = strtolower($question);

        // Market/Niche related questions
        if (
            str_contains($questionLower, 'niche') ||
            str_contains($questionLower, 'market') ||
            str_contains($questionLower, 'target')
        ) {

            $data['customer_segments'] = $this->getCustomerSegments();
            $data['sales_by_segment'] = $this->getSalesBySegment();
            $data['geographic_distribution'] = $this->getGeographicData();
        }

        // Sales related questions
        if (
            str_contains($questionLower, 'sales') ||
            str_contains($questionLower, 'revenue') ||
            str_contains($questionLower, 'performance')
        ) {

            $data['sales_trends'] = $this->getSalesTrends();
            $data['getServicePerformance'] = $this->getServicePerformance();
        }

        // Customer related questions
        if (
            str_contains($questionLower, 'customer') ||
            str_contains($questionLower, 'user') ||
            str_contains($questionLower, 'client')
        ) {

            // $data['customer_analytics'] = $this->getCustomerAnalytics();
            // $data['retention_metrics'] = $this->getRetentionMetrics();
        }

        // If no specific context found, get general business overview
        if (empty($data)) {
            // $data['business_overview'] = $this->getBusinessOverview();
        }

        return $data;
    }

    // Mock data methods for testing (replace with your actual database queries)
    private function getCustomerSegments(): array
    {
        // Replace this with your actual database structure
        try {
            return User::get()->toArray();
        } catch (\Exception $e) {
            \Log::error('Error retrieving data',[
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return [ 
                Notification::make()
                ->title('Failed to retrieve data')
                ->body('Please check your input and try again.')
                ->danger()
                ->send(),
            ];
        }
    }

    private function getSalesBySegment(): array
    {
        return Invoices::join('users', 'invoices.customer_id', '=', 'users.id')
            ->join('invoice_items', 'invoices.id', '=', 'invoice_items.invoice_id')
            ->select('invoice_items.description as segment')
            ->selectRaw('SUM(invoice_items.unit_price) as total_revenue')
            ->selectRaw('COUNT(DISTINCT invoices.id) as order_count')
            ->selectRaw('AVG(invoices.total_amount) as avg_order_value')
            ->groupBy('invoice_items.description')
            ->orderByDesc('total_revenue')
            ->get()
            ->toArray();
    }
    
    

    private function getGeographicData(): array
    {
        try {
            return Invoices::join('users', 'invoices.customer_id', '=', 'users.id')
                ->select('users.address')
                ->selectRaw('COUNT(DISTINCT users.id) as customer_count')
                ->selectRaw('COUNT(invoices.id) as total_invoices')
                ->selectRaw('SUM(invoices.total_amount) as total_revenue')
                ->selectRaw('AVG(invoices.total_amount) as avg_invoice_value')
                ->groupBy('users.address')
                ->orderBy('total_revenue', 'desc')
                ->limit(20)
                ->get()
                ->toArray();
        } catch (\Exception $e) {
            // return [
            //     ['country' => 'Pakistan', 'city' => 'Karachi', 'customer_count' => 200, 'total_revenue' => 400000],
            //     ['country' => 'Pakistan', 'city' => 'Lahore', 'customer_count' => 150, 'total_revenue' => 300000],
            //     ['country' => 'UAE', 'city' => 'Dubai', 'customer_count' => 100, 'total_revenue' => 250000],
            // ];
            \Log::error('Error retrieving data',[
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return [ 
                Notification::make()
                ->title('Failed to retrieve data')
                ->body('Please check your input and try again.')
                ->danger()
                ->send(),
            ];
        }
    }

    private function getSalesTrends(): array
    {
        $trends = [];
        for ($i = 30; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $trends[] = [
                'date' => $date,
                'daily_revenue' => rand(5000, 15000),
                'daily_orders' => rand(10, 30)
            ];
        }
        return $trends;
    }

    private function getServicePerformance(): array
    {
        return InvoiceItem::select('description')->toArray();

        // return [
        //     ['name' => 'Premium Plan', 'category' => 'Subscription', 'total_sold' => 150, 'total_revenue' => 450000],
        //     ['name' => 'Basic Plan', 'category' => 'Subscription', 'total_sold' => 300, 'total_revenue' => 180000],
        //     ['name' => 'Consulting Service', 'category' => 'Service', 'total_sold' => 50, 'total_revenue' => 125000],
        // ];
    }

    // private function getCustomerAnalytics(): array
    // {
    //     return [
    //         'total_customers' => 530,
    //         'new_customers_30d' => 45,
    //         'avg_customer_value' => 2100,
    //         'top_spending_segments' => [
    //             ['segment' => 'Enterprise', 'avg_spent' => 5000],
    //             ['segment' => 'SMB', 'avg_spent' => 1200],
    //             ['segment' => 'Startup', 'avg_spent' => 500],
    //         ]
    //     ];

    // }

    // private function getRetentionMetrics(): array
    // {
    //     return [
    //         ['segment' => 'Enterprise', 'total_customers' => 150, 'active_customers' => 140, 'avg_orders_per_customer' => 12],
    //         ['segment' => 'SMB', 'total_customers' => 300, 'active_customers' => 250, 'avg_orders_per_customer' => 8],
    //         ['segment' => 'Startup', 'total_customers' => 80, 'active_customers' => 60, 'avg_orders_per_customer' => 4],
    //     ];
    // }

    private function getBusinessOverview(): array
    {


        // get invoices
        $invoices = Invoices::all();
        // where role is customer
        $users = User::select('role')
            ->where('role', 'customer')
            ->get();
        // count users
        $totalCustomers = $users->count();
        $totalRevenue = $invoices->sum('total_amount');
        $averageOrderValue = $invoices->avg('total_amount');
        return [
            'total_customers' => $totalCustomers,
            'total_revenue' => $totalRevenue,
            'average_order_value' => $averageOrderValue,
        ];
    }
}

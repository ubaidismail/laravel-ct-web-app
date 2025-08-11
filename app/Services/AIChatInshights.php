<?php
// app/Services/GeminiService.php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AIChatInshights
{
    protected string $apiKey;
    protected string $apiUrl;

    public function __construct()
    {
        $this->apiKey = env('GROK_API_KEY');
        // $this->apiKey = config('services.gemini.key');
        $this->apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent';
    }
    // using gemeni
    // public function askWithContext(string $question, array $contextData): string
    // {
    //     $context = json_encode($contextData, JSON_PRETTY_PRINT);
        
    //     $prompt = "
    //     Based on this business data from our portal:
    //     {$context}
        
    //     User Question: {$question}
        
    //     Provide specific, actionable business guidance based on the data above.
    //     Focus on practical recommendations with supporting evidence from the data.
    //     ";
        
    //     $response = Http::post($this->apiUrl . '?key=' . $this->apiKey, [
    //         'contents' => [
    //             [
    //                 'parts' => [
    //                     ['text' => $prompt]
    //                 ]
    //             ]
    //         ]
    //     ]);
        
    //     if ($response->successful()) {
    //         $data = $response->json();
    //         return $data['candidates'][0]['content']['parts'][0]['text'] ?? 'No response received';
    //     }
        
    //     throw new \Exception('Gemini API request failed: ' . $response->body());
    // }

    // using grok model
public function askWithContext(string $question, array $contextData): string
{
    $context = json_encode($contextData, JSON_PRETTY_PRINT);
    
    $prompt = "
    Based on this business data from our portal:
    {$context}
    
    User Question: {$question}
    
    Provide specific, actionable business guidance based on the data above.
    Focus on practical recommendations with supporting evidence from the data.
    ";
    
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $this->apiKey,
        'Content-Type' => 'application/json',
    ])->post('https://api.groq.com/openai/v1/chat/completions', [
        // 'model' => 'llama-3.3-70b-versatile',
        'model' => 'qwen/qwen3-32b',
        'messages' => [
            [
                'role' => 'user',
                'content' => $prompt
            ]
        ],
        'temperature' => 0.5,
        'max_tokens' => 800,
    ]);
    
    if ($response->successful()) {
        $data = $response->json();
        return $data['choices'][0]['message']['content'] ?? 'No response received';
    }
    
    throw new \Exception('Llama API request failed: ' . $response->body());
    
}
}
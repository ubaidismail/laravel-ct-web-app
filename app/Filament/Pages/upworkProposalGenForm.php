<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Http;

class UpworkProposalGenForm extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.upwork-proposal-gen-form';

    public ?string $project_description = null;
    public ?string $result = null;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Textarea::make('project_description')
                    ->label('Project Description')
                    ->placeholder('Enter the project description here...')
                    ->required(),
            ])
            ->statePath('');
    }

    public function create()
    {
        $clientInput = $this->project_description;

        if (empty($clientInput)) {
            $this->result = 'Please provide a project description.';
            return;
        }

        $systemPrompt = "You are a professional Upwork proposal writer with extensive experience in winning bids. Your task is to create compelling, personalized proposals that demonstrate clear understanding of client requirements and position the freelancer as the ideal solution provider.

        When crafting proposals, follow these guidelines:
        1. Always starts with Hi or more personalized greeting avoid use of Dear or hiring manager or client
        2. Starts with focused hook that shows immediate understanding of the client's core problem.
        3. Show clear comprehension of the project by:
        - Restating key elements of the project to confirm understanding
        - Identifying unstated challenges the client may face
        - Highlighting critical success factors for the project
        - A winning Upwork proposal should be concise, personalized, and clearly demonstrate your understanding of the project and how you can help the client
        
        4. Offer a clear solution approach with:
        - A structured overview of your recommended methodology
        - Key milestones or deliverables
        - Specific technologies, tools, or frameworks you'll utilize but not necessory everytime
        
        5. Demonstrate specific expertise by referencing relevant experience, skills, and successful projects that align with the client's requirements.

        5. Include recent projects: https://meddipop.com/
        https://artdicacao.com/
        https://laam.pk/

        6. End with a specific call to action inviting further discussion (e.g looking forward to collaborating with you).

        7. Maintain a professional, confident tone that balances expertise with approachability.

        8. Avoid crafting too long but only where needed.

        9. Use the standard and human tone,

        DO NOT:
        - Include pricing information
        - Include Please feel free to contact me via email, phone, or Upwork messaging to schedule a call and discuss your project in more detail.
        - Use generic phrases like \"I'm excited about your project\"
        - Overpromise with phrases like \"I can deliver perfect results\"
        - List your entire work history or irrelevant skills
        - Copy and paste template language that lacks personalization
        - Use excessive technical jargon without explanation
        - Write excessively long proposals (stay under 300 words)";

        try {
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('GROK_API_KEY'),
                'Content-Type' => 'application/json',
            ])->timeout(60)
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => 'llama-3.3-70b-versatile',  
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => $systemPrompt
                        ],
                        [
                            'role' => 'user',
                            'content' => $clientInput
                        ]
                    ],
                    'temperature' => 0.5,
                    'max_tokens' => 800,
                ]);

            $output = $response->json();

            // $response = [
            //     "id" => "chatcmpl-d5dc4987-2853-48cb-8d01-f41b63b99571",
            //     "object" => "chat.completion",
            //     "created" => 1747777190,
            //     "model" => "llama-3.3-70b-versatile",
            //     "choices" => [
            //         [
            //             "index" => 0,
            //             "message" => [
            //                 "role" => "assistant",
            //                 "content" => "Dear [Client],\n\nI understand that you're seeking a reliable web application to streamline school admissions and fee payments. To confirm, the key requirements of this project include creating a user-friendly interface for parents to submit applications, managing student data, and facilitating secure online fee payments.\n\nIn addition to these core features, I've identified potential challenges such as ensuring data security, integrating with existing school systems, and providing timely notifications to parents and administrators. To address these concerns, I propose a comprehensive solution that incorporates robust security measures, API integrations, and customizable notification systems.\n\nThe critical success factors for this project include a seamless user experience, efficient data management, and timely payment processing. My approach will involve a structured methodology, consisting of the following key milestones: \n\n1. Requirements gathering and system design\n2. Front-end development using React and back-end development using Node.js\n3. Integration with a secure payment gateway, such as Stripe or PayPal\n4. Testing and quality assurance\n\nI'll utilize technologies like MySQL for database management and implement SSL encryption for secure data transmission. Similar projects I've worked on have resulted in a 30% reduction in administrative workload and a 25% increase in online fee payments.\n\nI'm confident in my ability to deliver a tailored web application that meets your school's specific needs. I look forward to collaborating with you to discuss this project further. You can reach me via Upwork messaging or schedule a call to explore how we can work together to create an efficient and secure school admissions and fee payment system."
            //             ],
            //             "logprobs" => null,
            //             "finish_reason" => "stop"
            //         ]
            //     ],
            //     "usage" => [
            //         "queue_time" => 0.204412787,
            //         "prompt_tokens" => 288,
            //         "prompt_time" => 0.018227054,
            //         "completion_tokens" => 293,
            //         "completion_time" => 1.065454545,
            //         "total_tokens" => 581,
            //         "total_time" => 1.083681599
            //     ],
            //     "usage_breakdown" => [
            //         "models" => null
            //     ],
            //     "system_fingerprint" => "fp_6507bcfb6f",
            //     "x_groq" => [
            //         "id" => "req_01jvqt6y31fyysdwyfxmqff15f"
            //     ]
            // ];
            
            // $output = $response;
            

            if ($response->successful() && isset($output['choices'][0]['message']['content'])) {
                $this->result = nl2br( $output['choices'][0]['message']['content'] );
            } else {
                $this->result = "API Error: " . ($output['error']['message'] ?? 'Unknown error');
            }

        } catch (\Exception $e) {
            $this->result = "Exception: " . $e->getMessage();
        }
    }

    public function copyToClipboard()
    {
       
    }
}

<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Http;
use Filament\Forms\Components\TextInput;
use App\Models\UpworkProposalGenQueries;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\RateLimiter;



class UpworkProposalGenForm extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Upwork Tool';

    protected static string $view = 'filament.pages.upwork-proposal-gen-form';

    public ?string $project_description = null;
    public ?string $user_id = null;
    public ?string $insert_your_portfolio = null;
    public ?string $result = null;
    public ?string $error = null;

    // Add this method to initialize the user_id
    public function mount(): void
    {
        $this->user_id = (string) Auth::id();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Use Hidden instead of TextInput for user_id
                Hidden::make('user_id')
                    ->default(fn() => Auth::id()),

                Textarea::make('project_description')
                    ->label('Project Description')
                    ->placeholder('Enter the project description here...')
                    ->required(),
                Textarea::make('insert_your_portfolio')
                    ->label('Add Portfolio or Links')
                    ->placeholder('Add Portfolio or Recent Projects Links Here')
                    ->required(),
            ])
            ->statePath(''); // This is fine, but we're handling user_id separately
    }

    public function create()
    {
        
        $get_ip = request()->ip(); // Get the user's IP address

        $key = 'proposal_gen_form_' . $get_ip;   // it creates a unique cache key per ip

        // 3 request per minute
        if(RateLimiter::tooManyAttempts($key, 3)){
            $seconds = RateLimiter::availableIn($key);
            $minutes = ceil($seconds / 60);

            Notification::make()
            ->title('Too Many Requests')
            ->body("Please try again after {$minutes} minutes.")
            ->danger()
            ->persistent()
            ->send();

            return;
        }
        RateLimiter::hit($key);

        // Get form data
        $data = $this->form->getState();

        $clientInput = $data['project_description'] ?? $this->project_description;
        $user_portfolio = $data['insert_your_portfolio'] ?? $this->insert_your_portfolio;

        // Ensure user_id is set
        if (!$this->user_id) {
            $this->user_id = (string) Auth::id();
        }

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

        5. Include recent projects or links if provided: $user_portfolio 

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
            // rate limit 3 request per minute

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

            if ($response->successful() && isset($output['choices'][0]['message']['content'])) {
                $this->result = nl2br($output['choices'][0]['message']['content']);
            } else {
                $this->result = "API Error: " . ($output['error']['message'] ?? 'Unknown error');
                $this->error = $output['error']['message'] ?? 'Unknown error';
            }

            // Update the component properties with form data
            $this->project_description = $clientInput;
            $this->insert_your_portfolio = $user_portfolio;

            $this->saveData();
        } catch (\Exception $e) {
            $this->result = "Exception: " . $e->getMessage();
        }
    }

    // Save data in db
    protected function saveData()
    {
        $data = [
            'user_id' => $this->user_id,
            'project_description' => $this->project_description,
            'portfolio' => $this->insert_your_portfolio,
            'AI_result' => $this->result,
            'error' => $this->error,
        ];

        UpworkProposalGenQueries::create($data);
    }
}

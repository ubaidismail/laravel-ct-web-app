<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\ProjectQuotes;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Support\View\Components\Modal;
use Filament\Widgets\Widget;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Mail\Attachable;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use App\Mail\NewProjectQuote;
use Illuminate\Support\Facades\Mail;



class CustomerDetailsPopup extends Widget implements HasForms
{
    use \Filament\Forms\Concerns\InteractsWithForms;


    protected static string $view = 'livewire.customer-details-popup';

    public $showModal = true;

    public $data = [
        'company_name' => '',
        'email' => '',
        'phone' => '',
        'country' => '',
        'project_name' => '',
        'project_description' => '',
        'budget' => '',
        'project_requirement' => [],
    ];


    public function mount()
    {
        $user = auth()->user();

        $this->data = [
            'company_name' => $user?->name,
            'email' => $user?->email,
            'phone' => '',
            'country' => '',
            'project_name' => '',
            'project_description' => '',
            'budget' => '',
            'project_requirement' => [],
        ];

        $project = ProjectQuotes::where('customer_id', $user->id)->first();
        if($project){
            $this->showModal = false;
        }
    }

    public function openModal(): void
    {
        $this->showModal = true;
    }
    
    public function closeModal(): void
    {
        $this->showModal = false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema($this->getFormSchema());
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make(2)->schema([
                TextInput::make('company_name')
                    ->label('Company Name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email')
                    ->required(),
                TextInput::make('phone')
                    ->numeric()
                    ->label('Phone')
                    ->required(),
                TextInput::make('country')
                    ->label('Country')
                    ->required(),
            ]),
            TextInput::make('project_name')
                ->label('Project Name')
                ->required(),
            Select::make('budget')
                ->label('Budget')
                ->placeholder('Select Your Estimated Budget')
                ->options([
                    'less then $10,000' => 'Less than $10,000',
                    '$10,000 to $50,000' => '$10,000 to $50,000',
                    'Above $50,000' => 'Above $50,000',
                ])
                ->required(),
            Textarea::make('project_description')
                ->label('Project Description')
                ->rows(3)
                ->maxLength(500)
                ->helperText('Please provide a brief description of your project.'),
            FileUpload::make('project_requirement')
                ->label('Project Requirement')
                ->helperText('Attach any relevant files or documents related to your project.')
                ->acceptedFileTypes(['application/pdf', 'image/*'])
                ->maxFiles(5)
                ->disk('local')
                ->directory('new_project_requirement'),
        ];
    }

    public function submit()
    {
        $fileNames = [];
        foreach ($this->data['project_requirement'] as $file) {
            // Store file in storage public folder and get the path
            $path = $file->store('new_project_requirement', 'public');

            $originalName = $file->getClientOriginalName();

            // Save both pieces of information
            $fileNames[] = [
                'original_name' => $originalName,
                'stored_path' => $path
            ];
        }

        // save in project db:
        $project = new ProjectQuotes();
        $project->company_name = $this->data['company_name'];
        $project->customer_id = auth()->user()->id;
        $project->email = $this->data['email'];
        $project->phone = $this->data['phone'];
        $project->country = $this->data['country'];
        $project->project_name = $this->data['project_name'];
        $project->budget = $this->data['budget'];
        $project->project_description = $this->data['project_description'];
        $project->project_requirements = json_encode($fileNames);
        $project->save();

        $adminMail = config('app.AdminMail');

        Mail::to($adminMail)->send(new NewProjectQuote($this->data));

        Notification::make()
            ->title('Your query has been received, we will get back to you soon.')
            ->success()
            ->send();

        // Close the modal
        $this->dispatch('close-modal', ['id' => 'customer-details-modal']);
        $this->showModal = false;
        $this->reset('data');
    }

    // Optional method to handle navigation visibility - keep if needed
    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user() && auth()->user()->user_role === 'customer';
    }
}

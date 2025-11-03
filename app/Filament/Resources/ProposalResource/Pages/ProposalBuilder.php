<?php

namespace App\Filament\Resources\ProposalResource\Pages;

use App\Filament\Resources\ProposalResource;
use App\Models\Proposals;
use Filament\Resources\Pages\Page;
use Filament\Notifications\Notification;
use Livewire\Attributes\Validate;

class ProposalBuilder extends Page
{
    protected static string $resource = ProposalResource::class;

    protected static string $view = 'filament.resources.proposal-resource.pages.proposal-builder';

    protected static ?string $title = 'Proposal Builder';

    public ?Proposals $record = null;
    
    // #[Validate('required|min:2|max:255', message: 'Please enter your name to sign the proposal.')]
    public $clientSignature = '';


    public function saveSignature()
    {
        // Test if this even gets called
        // dd('Method called!', $this->clientSignature);
        
        $this->validate([
            'clientSignature' => 'required|string|max:255',
        ]);

        try {
            $this->record->update([
                'client_signature' => $this->clientSignature,
                'date_signed' => now(),
            ]);

            Notification::make()
                ->title('Proposal Signed Successfully!')
                ->success()
                ->send();

            $this->clientSignature = '';
            
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->danger()
                ->body('Failed to save signature: ' . $e->getMessage())
                ->send();
        }
    }
    public function getLayout(): string
    {
        if (!auth()->check()) {
            return 'filament-panels::components.layout.base';
        }
        return parent::getLayout();
    }
    
    public static function canView(): bool
    {
        return true;
    }

    public static function shouldAuthorize(): bool
    {
        return false;
    }

    public static function getMiddleware(): array
    {
        return [];
    }

    
}
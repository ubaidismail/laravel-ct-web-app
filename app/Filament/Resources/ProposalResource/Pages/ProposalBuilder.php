<?php

namespace App\Filament\Resources\ProposalResource\Pages;

use App\Filament\Resources\ProposalResource;
use App\Models\Proposals;
use App\Models\ProposalVersions;
use Filament\Resources\Pages\Page;
use Filament\Notifications\Notification;
use Livewire\Attributes\Validate;

class ProposalBuilder extends Page
{
    protected static string $resource = ProposalResource::class;

    protected static string $view = 'filament.resources.proposal-resource.pages.proposal-builder';

    // public ?Proposals $record = null;
    // i want to check if url has query parameter version_number then it should get data from propsoal version table.
    public $record = null;

    /**
     * When the page is mounted, decide whether to use the base proposal
     * record or a specific version from the proposal_versions table,
     * based on the ?version_number= query parameter.
     */
    public function mount(Proposals $record): void
    {
        $versionNumber = request()->query('version'); // this is the primary id of proposal_versions table
        if ($versionNumber !== null) {
            $version = ProposalVersions::where('proposal_id', $record->id)
                ->where('id', $versionNumber)
                ->first();
                // echo $versionNumber;
            if ($version) {
                $this->record = $version;
                return;
            }
        }

        // Fallback to the main proposal record if no version is requested/found
        $this->record = $record;
    }
    public function getTitle(): string
    {
        // Access the record and return dynamic title
        return $this->record->proposal_name ?? 'Review and Sign the Proposal';
;
    }
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

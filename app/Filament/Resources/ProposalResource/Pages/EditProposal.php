<?php

namespace App\Filament\Resources\ProposalResource\Pages;

use App\Filament\Resources\ProposalResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Models\ProposalVersions;
use App\Models\Proposals;

class EditProposal extends EditRecord
{
    protected static string $resource = ProposalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
        
    }
    protected function beforeSave(): void
    {
        // Create a new version of the proposal with the current version number
        ProposalVersions::create([
            'version_number' => $this->record->version_number === 1 ? 1 : $this->record->version_number,
            'proposal_name' => $this->record->proposal_name,
            'prepared_for_customer_name' => $this->record->prepared_for_customer_name,
            'client_company_name'=> $this->record->client_company_name,
            'prepared_for_customer_email' => $this->record->prepared_for_customer_email,
            'prepared_for_customer_phone' => $this->record->prepared_for_customer_phone,
            'prepared_for_customer_address' => $this->record->prepared_for_customer_address,
            'proces_briefing' => $this->record->proces_briefing,
            'objective' => $this->record->objective,
            'process_details_in_bullets' => $this->record->process_details_in_bullets,
            'project_description' => $this->record->project_description,
            'payment_terms' => $this->record->payment_terms,
            'total_project_price' => $this->record->total_project_price,
            'client_signature' => $this->record->client_signature,
            'date_signed' => $this->record->date_signed,
            'proposal_type' => $this->record->proposal_type,
            'change_summary' => $this->record->change_summary,
            'changed_by' => $this->record->changed_by,
            'proposal_id' => $this->record->id,
            'send_as' => $this->record->send_as,
        ]);
        
        // Increment the version number in the proposals table
        $this->record->version_number = ($this->record->version_number ?? 1) + 1;
    } 
    


   
}

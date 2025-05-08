<?php

namespace App\Filament\Resources\ProjectQuotesResource\Pages;

use App\Filament\Resources\ProjectQuotesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\RewardPoints; // Assuming you have a model for RewardPoint
use App\Models\ReferalToken;

class EditProjectQuotes extends EditRecord
{
    protected static string $resource = ProjectQuotesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        // Get the current record after update
        $record = $this->getRecord();
        
        // Check if status has been changed to "started"
        if ($record->status === 'started') {
            
            // referrer means the user who referred the customer
            // dd($record->customer_id);
            // if ($record->referral_token) {
                // Find the user who owns this referral token
                $referrerUser = \App\Models\User::where('id', $record->customer_id)->first();
                // get referal code by customer from users table
                $referrerUser = \App\Models\User::where('referal_code', $referrerUser->referal_code)->first();

                $ReferalToken = ReferalToken::where('token', $referrerUser->referal_code)->first();

                if ($referrerUser) {
                    
                    // Check if a reward point already exists for this project
                    $existingReward = RewardPoints::where('project_quote_id', $record->id)->first();
                    
                    if (!$existingReward) {
                        // Create a new reward point
                        RewardPoints::create([
                            'project_quote_id' => $record->id,
                            'referrer_user_id' => $ReferalToken->referrer_user_id,
                            'token_id' => $ReferalToken->id,
                            'status' => $record->status,
                            'amount' => $record->total_project_amount, // Or calculate based on project value
                            'percent_markup' => 5, // Or calculate based on project value
                            'referal_type' => $ReferalToken->token,
                            'issued_at' => now(),
                            'status' => $record->status,
                        ]);
                    }
                }
            // }
        }
    }
}

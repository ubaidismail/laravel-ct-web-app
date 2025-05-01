<?php

namespace App\Filament\Resources\ReferralRewardPointResource\Pages;

use App\Filament\Resources\ReferralRewardPointResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReferralRewardPoint extends EditRecord
{
    protected static string $resource = ReferralRewardPointResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
        
    }

}

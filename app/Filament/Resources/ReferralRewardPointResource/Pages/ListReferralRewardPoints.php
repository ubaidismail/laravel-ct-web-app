<?php

namespace App\Filament\Resources\ReferralRewardPointResource\Pages;

use App\Filament\Resources\ReferralRewardPointResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReferralRewardPoints extends ListRecords
{
    protected static string $resource = ReferralRewardPointResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

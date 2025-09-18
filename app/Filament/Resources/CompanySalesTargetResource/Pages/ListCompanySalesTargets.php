<?php

namespace App\Filament\Resources\CompanySalesTargetResource\Pages;

use App\Filament\Resources\CompanySalesTargetResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCompanySalesTargets extends ListRecords
{
    protected static string $resource = CompanySalesTargetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

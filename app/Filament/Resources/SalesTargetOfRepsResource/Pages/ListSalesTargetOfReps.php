<?php

namespace App\Filament\Resources\SalesTargetOfRepsResource\Pages;

use App\Filament\Resources\SalesTargetOfRepsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSalesTargetOfReps extends ListRecords
{
    protected static string $resource = SalesTargetOfRepsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

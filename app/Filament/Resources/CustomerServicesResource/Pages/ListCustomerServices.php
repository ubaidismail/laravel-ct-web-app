<?php

namespace App\Filament\Resources\CustomerServicesResource\Pages;

use App\Filament\Resources\CustomerServicesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCustomerServices extends ListRecords
{
    protected static string $resource = CustomerServicesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }


}

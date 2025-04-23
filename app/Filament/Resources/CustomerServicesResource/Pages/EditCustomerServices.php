<?php

namespace App\Filament\Resources\CustomerServicesResource\Pages;

use App\Filament\Resources\CustomerServicesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCustomerServices extends EditRecord
{
    protected static string $resource = CustomerServicesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\CompanyServicesResource\Pages;

use App\Filament\Resources\CompanyServicesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCompanyServices extends EditRecord
{
    protected static string $resource = CompanyServicesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

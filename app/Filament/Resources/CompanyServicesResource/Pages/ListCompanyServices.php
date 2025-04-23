<?php

namespace App\Filament\Resources\CompanyServicesResource\Pages;

use App\Filament\Resources\CompanyServicesResource;
use App\Models\services;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;



class ListCompanyServices extends ListRecords
{
    protected static string $resource = CompanyServicesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

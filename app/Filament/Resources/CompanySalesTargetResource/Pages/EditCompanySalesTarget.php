<?php

namespace App\Filament\Resources\CompanySalesTargetResource\Pages;

use App\Filament\Resources\CompanySalesTargetResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCompanySalesTarget extends EditRecord
{
    protected static string $resource = CompanySalesTargetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

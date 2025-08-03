<?php

namespace App\Filament\Resources\AIInsightReportResource\Pages;

use App\Filament\Resources\AIInsightReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAIInsightReport extends EditRecord
{
    protected static string $resource = AIInsightReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

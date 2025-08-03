<?php

namespace App\Filament\Resources\AIInsightReportResource\Pages;

use App\Filament\Resources\AIInsightReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAIInsightReports extends ListRecords
{
    protected static string $resource = AIInsightReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('generate_new')
                ->label('Generate New Insights')
                ->icon('heroicon-o-sparkles')
                ->url(fn (): string => route('filament.admin.pages.generate-insights'))
                ->color('primary'),
        ];
    }
}
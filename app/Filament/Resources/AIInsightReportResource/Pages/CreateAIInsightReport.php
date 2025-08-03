<?php

namespace App\Filament\Resources\AIInsightReportResource\Pages;

use App\Filament\Resources\AIInsightReportResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAIInsightReport extends CreateRecord
{
    protected static string $resource = AIInsightReportResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
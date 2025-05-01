<?php

namespace App\Filament\Resources\ProjectQuotesResource\Pages;

use App\Filament\Resources\ProjectQuotesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProjectQuotes extends ListRecords
{
    protected static string $resource = ProjectQuotesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

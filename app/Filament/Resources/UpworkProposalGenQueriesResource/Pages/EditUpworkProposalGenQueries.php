<?php

namespace App\Filament\Resources\UpworkProposalGenQueriesResource\Pages;

use App\Filament\Resources\UpworkProposalGenQueriesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUpworkProposalGenQueries extends EditRecord
{
    protected static string $resource = UpworkProposalGenQueriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

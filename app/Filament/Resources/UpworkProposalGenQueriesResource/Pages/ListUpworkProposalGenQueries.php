<?php

namespace App\Filament\Resources\UpworkProposalGenQueriesResource\Pages;

use App\Filament\Resources\UpworkProposalGenQueriesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;

class ListUpworkProposalGenQueries extends ListRecords
{
    protected static string $resource = UpworkProposalGenQueriesResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\CreateAction::make(),  ===>>>>>>>> DEfault
    //     ];
    // }
    protected function getHeaderActions(): array
    {
        return [
            Action::make('GenerateProposal')
                ->label('Generate Proposal')
                ->url(fn () => url('/upwork-proposal-gen-form')) // Adjust path if needed
                ->icon('heroicon-o-plus')
                ->color('primary'),
        ];


    }
}

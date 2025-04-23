<?php

namespace App\Filament\CustomerPanel\Pages;

use App\Models\CustomerServices;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Database\Eloquent\Builder;



class MyServices extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.customer-panel.pages.my-services';

    // get data from customer services table by user id and show in table

    protected function getTableQuery(): Builder
    {
        return CustomerServices::query()
            ->where('customer_id', auth()->id());
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('service_name')->label('Service Name'),
            TextColumn::make('service_description')->label('Details'),
            TextColumn::make('service_duration')->label('Duration'),
            TextColumn::make('start_date')->dateTime('M d Y')->label('Start Date'),
            TextColumn::make('end_date')->dateTime('M d Y')
                ->badge()
                ->color('danger')
                ->label('Expires At'),
            TextColumn::make('service_status')
                // capitalise the first letter of the string
                ->getStateUsing(function (CustomerServices $record) {
                    return ucfirst($record->service_status);
                })
                ->badge()
                ->color('success')
                ->label('Status'),

        ];
    }
    protected function getTableActions(): array
    {
        return [
            Tables\Actions\Action::make('view')
                ->label('View Details')
                ->icon('heroicon-o-eye')
                ->modalHeading('Service Details')
                ->modalSubheading(fn (CustomerServices $record) => 'Details for: ' . $record->service_name)
                ->modalButton('Close')
                ->modalContent(fn (CustomerServices $record) => view('filament.customer-panel.pages.partials.service-details', [
                    'record' => $record,
                ])),
        ];
    }
    
    // model content 


}

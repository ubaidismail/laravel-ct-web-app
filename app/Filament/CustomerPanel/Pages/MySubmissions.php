<?php

namespace App\Filament\CustomerPanel\Pages;

use App\Models\ProjectQuotes;
use App\Models\User;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Container\Attributes\Storage;
use Illuminate\Database\Eloquent\Builder;


class MySubmissions extends Page implements HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.customer-panel.pages.my-submissions';

    // get data in table

    protected function getTableQuery(): Builder
    {
        return ProjectQuotes::query()
            ->where('customer_id', auth()->id());
    }
    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('id')
                ->label('ID')
                ->searchable()
                ->sortable(),
            TextColumn::make('project_name')
                ->label('Project Name')
                ->searchable()
                ->sortable(),
            TextColumn::make('budget')
                ->label('Project Budget')
                ->searchable()
                ->sortable(),
            TextColumn::make('budget')
                ->label('Project Budget')
                ->searchable()
                ->sortable(),

            TextColumn::make('project_description')
                ->label('Project Description')
                ->searchable()
                ->limit(20)
                ->sortable(),
            TextColumn::make('email')
                ->label('Email')
                ->searchable()
                ->limit(20)
                ->sortable(),
            TextColumn::make('company_name')
                ->label('Company Name')
                ->searchable()
                ->limit(20)
                ->sortable(),
                
                // get project requirements field. it;s a file
                TextColumn::make('project_requirements')
                ->label('Project Requirements')
                ->searchable()
                ->sortable()
                
                ->formatStateUsing(function ($state) {
                    if ($state) {
                        $state = json_decode($state, true);
                        if(!empty($stored_path)){
                            $stored_path = asset('storage/'. $state['stored_path']);
                            $original_name = $state['original_name'];
                            return "<a href='$stored_path' target='_blank' download=".$original_name.">$original_name</a>";
                     }
                    }

                    return '-';
                }) -> html(),

                TextColumn::make('is_started')
                ->label('Status')
                ->badge()
                ->color(function ($state) {
                    return $state == 0 ? 'warning' : 'success';
                })
                ->formatStateUsing(function ($state) {
                    return $state == 0 ? 'Pending' : 'Started';
                })
                ->searchable()
                ->sortable(), 
                
                
                TextColumn::make('created_at')
                ->label('Created At')
                ->formatStateUsing(function ($state) {
                    return $state->format('M d, Y');
                })
                ->searchable()
                ->sortable(),   



        ];
    }
}

<?php

namespace App\Filament\CustomerPanel\Pages;

use Filament\Pages\Page;

use App\Models\invoices;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Filament\Support\Enums\FontWeight;

class MyInovices extends Page  implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.customer-panel.pages.my-inovices';

    protected function getTableQuery(): Builder
    {
        return invoices::query()
            ->where('customer_id', auth()->id());
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('id')->label('Invoice ID'),
            TextColumn::make('company_name')->label('Name'),
            TextColumn::make('total_amount')->label('Amount')
                ->money('USD', true)
                ->formatStateUsing(function (invoices $record) {
                    return '$' . number_format($record->total_amount, 2);
                }),

                TextColumn::make('invoice_type')->label('Invoice Type')
                    ->searchable()
                    ->color(fn($state) => $state === 'pending' ? 'danger' : 'success')
                    ->weight(FontWeight::Bold)
                    ->badge()
                    ->formatStateUsing(fn($state) => ucwords($state)),

                    TextColumn::make('due_date')
                    ->datetime('M d Y')
                    ->label('Due Date'),

                TextColumn::make('paid_date_formatted')
                    ->datetime('M d Y')
                    ->badge()
                    ->color('success')
                    ->label('Paid Date'),
                
                    TextColumn::make('project_name')
                    ->label('Download Invoice')
                    ->icon('heroicon-o-arrow-down')
                    ->url(fn (Invoices $record): string => route('download.invoice', ['invoice_id' => $record->id]))
                    ->limit(10)
        ];
    }
}

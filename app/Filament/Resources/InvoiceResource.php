<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource\RelationManagers;
use App\Models\Invoices;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\HtmlString;
use Filament\Support\Enums\FontWeight;
use Carbon\Carbon;
use Filament\Tables\Actions;




class InvoiceResource extends Resource
{
    protected static ?string $model = Invoices::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Invoices';

    public static function shouldRegisterNavigation(): bool
    {
        // Check if the user is an admin
        return auth()->user() && auth()->user()->user_role === 'admin';
    }
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table

            ->columns([
                TextColumn::make('id')
                ->searchable()->label('Invoice ID'),
                TextColumn::make('company_name')->searchable()->label('Name'),

                TextColumn::make('total_amount')->label('Amount')
                    ->formatStateUsing(function ($state, $record) {
                        return $record->client_currency . ' ' . number_format($state, 2);
                    }),

                TextColumn::make('address'),
                TextColumn::make('invoice_type')->label('Invoice Type')
                    ->searchable()
                    ->color(fn($state) => $state === 'pending' ? 'danger' : 'success')
                    ->weight(FontWeight::Bold)
                    ->badge()
                    ->formatStateUsing(fn($state) => ucwords($state)),

                TextColumn::make('due_date')->label('Due Date')
                    ->formatStateUsing(fn($state) => Carbon::parse($state)->format('M d Y')),

                TextColumn::make('paid_date')->label('Paid Date'),


                    TextColumn::make('project_name')
                    ->label('Download Invoice')
                    ->icon('heroicon-o-arrow-down')
                    ->url(fn (Invoices $record): string => route('download.invoice', ['invoice_id' => $record->id]))
                    ->limit(10)


            ])
            ->defaultSort('id', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make('delete')
                    ->requiresConfirmation()
                    ->action(fn(Invoices $record) => $record->delete())
            ])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function canCreate(): bool
    {
        return false;
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create-invoice'),
            'edit' => Pages\editInvoice::route('/edit-invoice/{record}'),

        ];
    }
    public static function getWidgets(): array
    {
        return [
            // Widgets\RevenueChart::class,
            
        ];
    }
}

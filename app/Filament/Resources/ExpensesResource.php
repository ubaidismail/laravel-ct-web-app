<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExpensesResource\Pages;
use App\Filament\Resources\ExpensesResource\RelationManagers;
use App\Models\Expenses;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Get;
use Illuminate\Support\Facades\Storage;

class ExpensesResource extends Resource
{
    protected static ?string $model = Expenses::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('expense_type')
                    ->label('Expense Type')
                    ->options([
                        'fixed_expense' => 'Fixed Expense',
                        'dynamic_expense' => 'Dynamic Expense',
                        'other' => 'Other',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('expense_name')
                    ->label('Expense Name')
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->label('Amount')
                    ->numeric()
                    ->required(),
                Forms\Components\DatePicker::make('expense_date')
                    ->label('Expense Date'),
                Forms\Components\Toggle::make('is_recurring')
                    ->label('Is Recurring?')
                    ->live(), // required to reactively show/hide fields

                Forms\Components\Select::make('recurrence_pattern')
                    ->label('Recurrence Pattern')
                    ->visible(fn(Get $get) => $get('is_recurring'))
                    ->options([
                        'daily' => 'Daily',
                        'weekly' => 'Weekly',
                        'monthly' => 'Monthly',
                        'yearly' => 'Yearly',
                    ]),

                Forms\Components\DatePicker::make('next_occurrence')
                    ->label('Next Occurrence')
                    ->visible(fn(Get $get) => $get('is_recurring')),

                Forms\Components\FileUpload::make('receipt_url')
                    ->label('Receipt Upload'),
                Forms\Components\Textarea::make('notes')
                    ->label('Notes'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('expense_type')->label('Expense Type'),
                Tables\Columns\TextColumn::make('expense_name')->label('Expense Name'),
                Tables\Columns\TextColumn::make('amount')->label('Amount')->money('usd', true),
                Tables\Columns\TextColumn::make('expense_date')->label('Expense Date'),
                Tables\Columns\IconColumn::make('is_recurring')
                    ->label('Recurring')
                    ->boolean(),
                Tables\Columns\TextColumn::make('recurrence_pattern')->label('Recurrence Pattern'),
                Tables\Columns\TextColumn::make('next_occurrence')->label('Next Occurrence'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('receipt')
                    ->label(fn($record) => $record->receipt_url ? 'Download Receipt' : 'No Receipt')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color(fn($record) => $record->receipt_url ? 'primary' : 'gray')
                    ->visible(fn($record) => (bool) $record->receipt_url)
                    ->action(fn($record) => response()->download(storage_path('app/public/' . $record->receipt_url))),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExpenses::route('/'),
            'create' => Pages\CreateExpenses::route('/create'),
            'edit' => Pages\EditExpenses::route('/{record}/edit'),
        ];
    }
}

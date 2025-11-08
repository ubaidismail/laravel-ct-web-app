<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanySalesTargetResource\Pages;
use App\Filament\Resources\CompanySalesTargetResource\RelationManagers;
use App\Models\CompanySalesTargets;
use App\Models\invoices;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Carbon\Carbon;

class CompanySalesTargetResource extends Resource
{
    protected static ?string $model = CompanySalesTargets::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Company';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)->schema([
                    TextInput::make('company_name')
                        ->label('Company Name')
                        ->required()
                        ->default('CloudTach')
                        ->maxLength(255),

                    TextInput::make('sales_target')
                        ->label('Sales Target')
                        ->required()
                        ->numeric()
                        ->minValue(0),



                    DatePicker::make('period')
                        ->label('Period')
                        ->required()

                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('company_name')
                    ->label('Company Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('sales_target')
                    ->label('Sales Target')
                    ->prefix('$')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('total_sales')
                    ->label('Actual Sales')
                    ->prefix('$')
                    ->getStateUsing(function ($record) {
                        $period = Carbon::parse($record->period);

                        return invoices::whereYear('due_date', $period->year)
                            ->whereMonth('due_date', $period->month)
                            ->sum('total_amount');
                    })

                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Target Status')
                    ->badge()
                    ->getStateUsing(function ($record) {
                        $period = Carbon::parse($record->period);

                        $totalSales = invoices::whereYear('due_date', $period->year)
                            ->whereMonth('due_date', $period->month)
                            ->sum('total_amount');

                        if ($totalSales >= $record->sales_target) {
                            return 'Achieved';
                        }
                        return 'Not Achieved';
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'Achieved' => 'success',
                        'Not Achieved' => 'danger',
                    }),

                TextColumn::make('period')
                    ->label('Period')
                    ->formatStateUsing(fn($state) => Carbon::parse($state)->format('M Y'))
                    ->searchable()
                    ->sortable()

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListCompanySalesTargets::route('/'),
            'create' => Pages\CreateCompanySalesTarget::route('/create'),
            'edit' => Pages\EditCompanySalesTarget::route('/{record}/edit'),
        ];
    }
}

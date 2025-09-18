<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SalesTargetOfRepsResource\Pages;
use App\Filament\Resources\SalesTargetOfRepsResource\RelationManagers;
use App\Models\SalesTargetOfReps;
use App\Models\User;
use Dom\Text;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SalesTargetOfRepsResource extends Resource
{
    protected static ?string $model = SalesTargetOfReps::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)->schema([
                    Select::make('rep_id')
                    ->label('Select Reps')
                    ->options(fn () => User::whereIn('user_role', ['sales_rep', 'admin'])
                        ->orderBy('id', 'desc')
                        ->pluck('name', 'id'))
                    ->searchable()
                    ->required()
                    ->placeholder('Select a Sales Rep')
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($user = User::find($state)) {
                            $set('rep_name', $user->name);
                        } else {
                            $set('rep_name', null);
                        }
                    }),
                
                TextInput::make('rep_name')
                    ->label('Rep Name')
                    ->readOnly()
                    ->placeholder('Rep Name')
                    ->live(),
                

                    TextInput::make('target')
                        ->label('Target')
                        ->required()
                        ->placeholder('Target'),

                    TextInput::make('mtd_sales')
                        ->label('Sales Closed This Month')
                        ->required()
                        ->placeholder('Sales Closed This Month')
                ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('rep_name')
                    ->label('Rep Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('target')
                    ->label('Target')
                    ->prefix('$')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('mtd_sales')
                    ->label('Sales Closed This Month')
                    ->searchable()
                    ->prefix('$')
                    ->sortable(),
                TextColumn::make('Target Month')
                    ->getStateUsing(fn (SalesTargetOfReps $record) => $record->created_at->format('M Y'))
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
            'index' => Pages\ListSalesTargetOfReps::route('/'),
            'create' => Pages\CreateSalesTargetOfReps::route('/create'),
            'edit' => Pages\EditSalesTargetOfReps::route('/{record}/edit'),
        ];
    }
}

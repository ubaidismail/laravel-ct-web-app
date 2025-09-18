<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyServicesResource\Pages;
use App\Filament\Resources\CompanyServicesResource\RelationManagers;
use App\Models\services;
use Filament\Forms;
use App\Models\User;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class CompanyServicesResource extends Resource
{
    protected static ?string $model = services::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Company Services';
    protected static ?string $label = 'Company Service';
    protected static ?string $navigationGroup = 'Company';

    public static function shouldRegisterNavigation(): bool {
        return auth()->user() && auth()->user()->user_role === 'admin';
       }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('service_name')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('service_name')->sortable()->searchable(),
                TextColumn::make('created_at')->dateTime()->sortable(),
                TextColumn::make('updated_at')->dateTime()->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListCompanyServices::route('/'),
            'create' => Pages\CreateCompanyServices::route('/create'),
            'edit' => Pages\EditCompanyServices::route('/{record}/edit'),
        ];
    }
}

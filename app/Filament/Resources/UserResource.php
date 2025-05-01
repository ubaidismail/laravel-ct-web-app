<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;


class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

   public static function shouldRegisterNavigation(): bool {
    return auth()->user() && auth()->user()->user_role === 'admin';
   }
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                TextInput::make('email')->email()->required(),
                Select::make('user_role')
                    ->options([
                        'admin' => 'Admin',
                        'customer' => 'customer',
                        'manager' => 'Manager',
                        'employee' => 'Employee',
                    ])
                    ->required()->placeholder('Select User Role'),
                TextInput::make('phone')->required(),
                TextInput::make('address')->required(),
                TextInput::make('project')->required(),
                TextInput::make('currency')->required(),
                TextInput::make('company')->required(),
                TextInput::make('total_amount_paid')->numeric(true)
                ->step(0.01)
                ->placeholder('Enter amount paid'),
                TextInput::make('password')
                    ->password()
                    ->required()
                    ->dehydrateStateUsing(fn($state) => bcrypt($state))
                    ->visibleOn('create'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('name')->searchable(),
                TextColumn::make('email')->searchable(),
                TextColumn::make('user_role')->searchable(),
                TextColumn::make('')->searchable()->label('Login As')->getStateUsing(function (User $record) {
                    // return $record->name;
                }),
                TextColumn::make('created_at')->date()->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make('Edit'),
    
                Tables\Actions\DeleteAction::make('delete')
                ->requiresConfirmation()
                ->action(fn (User $record) => $record->delete())
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('id', 'desc');
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}

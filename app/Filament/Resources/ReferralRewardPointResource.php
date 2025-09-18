<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReferralRewardPointResource\Pages;
use App\Filament\Resources\ReferralRewardPointResource\RelationManagers;
use App\Models\RewardPoints;
use App\Models\ReferalToken;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReferralRewardPointResource extends Resource
{
    protected static ?string $model = RewardPoints::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Referral Reward Points';
    protected static ?string $label = 'Referral Reward Point';
    protected static ?string $navigationGroup = 'Company';
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('referrer_user_id')
                    ->label('Referrar User')
                    ->options(User::all()->pluck('name', 'id'))
                    ->required(),
                Select::make('token_id')
                    ->label('Token')
                    ->options(ReferalToken::all()->pluck('token', 'id'))
                    ->required(),
                TextInput::make('amount')
                    ->label('Amount')
                    ->required(),
                TextInput::make('percent_markup')
                    ->label('Percent Markup')
                    ->required(),
                TextInput::make('issued_at')
                    ->label('Issued At')
                    ->required(),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('referrer_user_id')
                    ->label('Referrar User')
                    
                    // get ueer name by id
                    ->getStateUsing(function (RewardPoints $record) {
                        return User::find($record->referrer_user_id)->name;
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('token_id')
                    ->label('Token')
                    // get token by id
                    ->getStateUsing(function (RewardPoints $record) {
                        return ReferalToken::find($record->token_id)->token;
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('amount')
                    ->label('Amount')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('percent_markup')
                    ->label('Percent Markup')
                    ->searchable()
                    ->sortable(),
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
            'index' => Pages\ListReferralRewardPoints::route('/'),
            'create' => Pages\CreateReferralRewardPoint::route('/create'),
            'edit' => Pages\EditReferralRewardPoint::route('/{record}/edit'),
        ];
    }
}

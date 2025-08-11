<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UpworkProposalGenQueriesResource\Pages;
use App\Filament\Resources\UpworkProposalGenQueriesResource\RelationManagers;
use App\Models\UpworkProposalGenQueries;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;


class UpworkProposalGenQueriesResource extends Resource
{
    protected static ?string $model = UpworkProposalGenQueries::class;
    protected static ?string $navigationGroup = 'Upwork Tool';
    protected static ?string $navigationLabel = 'View Generated Data';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Use Hidden instead of TextInput for user_id
                Hidden::make('user_id')
                    ->default(fn() => Auth::id()),

                Textarea::make('project_description')
                    ->label('Project Description')
                    ->placeholder('Enter the project description here...')
                    ->required(),
                Textarea::make('portfolio')
                    ->label('Add Portfolio or Links')
                    ->placeholder('Add Portfolio or Recent Projects Links Here')
                    ->required(),
                Select::make('conversion_rate')
                    ->label('Conversion Rate')
                    ->options([
                        'no-conversion' => 'No conversion',
                        'interview-only' => 'Interviewed Only',
                        'hired' => 'Hired'
                    ])
                    ->required()
                    ->default('1'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User Name')
                    ->searchable()
                    ->formatStateUsing(fn ($state) => ucfirst($state))
                    ->sortable(),
                Tables\Columns\TextColumn::make('project_description')
                    ->label('Project Description')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('conversion_rate')
                ->label('Conversion Rate')
                ->badge()
                ->searchable()
                ->color(fn (string $state): string => match ($state) {
                    'no-conversion' => 'danger',
                    'interview-only' => 'warning',
                    'hired' => 'success',
                    default => 'gray',
                })
                ->formatStateUsing(fn ($state) => ucfirst($state)),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable('DESC')
                    ->toggleable(isToggledHiddenByDefault: true),  
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('viewDetails')
                        ->label('View Details')
                        ->icon('heroicon-o-eye')
                        ->color('info')
                        ->modalHeading(fn($record) => 'Proposal Details')
                        ->modalWidth('7xl')
                        ->modalSubmitAction(false)
                        ->modalCancelActionLabel('Close')
                        ->modalContent(fn($record) => view('filament.resources.upwork-proposal-gen-queries-resource.details', ['record' => $record])),
                    Tables\Actions\DeleteAction::make(),
                ])
                ->label('Actions')
                ->icon('heroicon-m-ellipsis-vertical')
                ->size('sm')
                ->color('gray')
                ->button(),  
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
            
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUpworkProposalGenQueries::route('/'),
            // 'create' => Pages::route('/create'),
            'edit' => Pages\EditUpworkProposalGenQueries::route('/{record}/edit'),
        ];
    }
    // disable edit

    public static function canEdit(Model $record): bool
    {
        return true;
    }
}

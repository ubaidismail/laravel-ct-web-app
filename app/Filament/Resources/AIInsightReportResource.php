<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AIInsightReportResource\Pages;
use App\Filament\Resources\AIInsightReportResource\RelationManagers;
use App\Models\AIInsightReport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AIInsightReportResource extends Resource
{
    protected static ?string $model = AIInsightReport::class;
    protected static ?string $navigationLabel = 'AI Insights Reports (BETA)';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('type')
                ->options([
                    'revenue_analysis' => 'Revenue Analysis',
                    'service_performance' => 'Service Performance',
                    'customer_insights' => 'Customer Insights',
                    'forecast' => 'Revenue Forecast',
                ])
                ->required(),
                
            Forms\Components\DateTimePicker::make('generated_at')
                ->required(),
                
            Forms\Components\Select::make('user_id')
                ->relationship('user', 'name')
                ->required(),
                
            Forms\Components\KeyValue::make('data')
                ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type_display')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Revenue Analysis' => 'success',
                        'Service Performance' => 'info',
                        'Customer Insights' => 'warning',
                        'Revenue Forecast' => 'danger',
                        default => 'gray',
                    }),
                    
                Tables\Columns\TextColumn::make('generated_at')
                    ->dateTime()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('user.name')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'generating' => 'warning',
                        'failed' => 'danger',
                        default => 'gray',
                    }),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'revenue_analysis' => 'Revenue Analysis',
                        'service_performance' => 'Service Performance',
                        'customer_insights' => 'Customer Insights',
                        'forecast' => 'Revenue Forecast',
                    ]),
                    
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from'),
                        Forms\Components\DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAIInsightReports::route('/'),
            'create' => Pages\CreateAIInsightReport::route('/create'),
            'view' => Pages\ViewAIInsightReport::route('/{record}'),
            'edit' => Pages\EditAIInsightReport::route('/{record}/edit'),
        ];
    }
} 


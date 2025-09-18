<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectQuotesResource\Pages;
use App\Filament\Resources\ProjectQuotesResource\RelationManagers;
use App\Models\ProjectQuotes as CustomerProjectSubmission;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProjectQuotesResource extends Resource
{
    protected static ?string $model = CustomerProjectSubmission::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'New Project Quotations';
    protected static ?string $navigationGroup = 'Company';

    public static function form(Form $form): Form
    {
        return $form
            // ->schema([
            ->schema([
                TextInput::make('company_name')
                    ->label('Company Name')
                    ->required(),
                TextInput::make('customer_id')
                    ->hidden(),
                TextInput::make('email')
                    ->label('Email')
                    ->required(),
                TextInput::make('phone')
                    ->numeric()
                    ->label('Phone')
                    ->required(),
                TextInput::make('country')
                    ->label('Country')
                    ->required(),
                TextInput::make('project_name')
                    ->label('Project Name')
                    ->required(),
                Select::make('budget')
                    ->label('Project Budget')
                    ->options([
                        'less then $10,000' => 'Less than $10,000',
                        '$10,000 to $50,000' => '$10,000 to $50,000',
                        'Above $50,000' => 'Above $50,000',
                    ])
                    ->placeholder('Select Your Estimated Budget')
                    ->required(),
                    TextInput::make('total_project_amount')
                    ->label('Project Amount')
                    ->required(),
                Forms\Components\Textarea::make('project_description')
                    ->label('Project Description')
                    ->required(),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'started' => 'Started',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->placeholder('Select Status')
                    ->required(),

                FileUpload::make('project_requirements')
                    ->label('Project Requirements')
                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                    ->maxSize(1024)
                    ->preserveFilenames()

            ]);
        // ])->columns(2)->columnSpan(2);
        // ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('project_name')
                    ->label('Project Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('budget')
                    ->label('Project Budget')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('budget')
                    ->label('Project Budget')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('project_description')
                    ->label('Project Description')
                    ->searchable()
                    ->limit(20)
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->limit(20)
                    ->sortable(),
                TextColumn::make('company_name')
                    ->label('Company Name')
                    ->searchable()
                    ->limit(20)
                    ->sortable(),

                // get project requirements field. it;s a file
                TextColumn::make('project_requirements')
                    ->label('Project Requirements')
                    ->searchable()
                    ->sortable()

                    ->formatStateUsing(function ($state) {
                        if ($state) {
                            $state = json_decode($state, true);
                            if (!empty($state['stored_path'])) {
                                $stored_path = asset('storage/' . $state['stored_path']);
                                $original_name = $state['original_name'];
                                return "<a href='$stored_path' target='_blank' download=" . $original_name . ">$original_name</a>";
                            }
                        }

                        return '-';
                    })->html(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(function ($state) {
                        return $state ==  'pending' || $state == 'cancelled' ? 'warning' : 'success';
                    })
                    ->formatStateUsing(function ($state) {
                        return ucfirst($state);
                    })
                    ->searchable()
                    ->sortable(),


                TextColumn::make('created_at')
                    ->label('Created At')
                    ->formatStateUsing(function ($state) {
                        return $state->format('M d, Y');
                    })
                    ->searchable()
                    ->sortable(),
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
            
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjectQuotes::route('/'),
            'create' => Pages\CreateProjectQuotes::route('/create'),
            'edit' => Pages\EditProjectQuotes::route('/{record}/edit'),
        ];
    }
}

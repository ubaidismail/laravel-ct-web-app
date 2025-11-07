<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProposalResource\Pages;
use App\Filament\Resources\ProposalResource\RelationManagers;
use App\Models\Proposals;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\{Grid, TextInput, Select, Repeater, DatePicker, Textarea, Section, Placeholder};
use Filament\Forms\Components\RichEditor;

class ProposalResource extends Resource
{
    protected static ?string $model = Proposals::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    /**
     * Reusable schema for client and project details.
     */
    public static function detailsSchema(): array
    {
        return [
            Grid::make(2)->schema([
                Forms\Components\TextInput::make('proposal_name')
                    ->label('Proposal name')
                    ->required(),
                Forms\Components\TextInput::make('client_company_name')
                    ->label('Company name')
                    ->required(),
                Forms\Components\TextInput::make('prepared_for_customer_name')
                    ->label('Customer name')
                    ->required(),
                Forms\Components\TextInput::make('prepared_for_customer_email')
                    ->label('Customer email')
                    ->email()
                    ->required(),
                Forms\Components\TextInput::make('prepared_for_customer_phone')
                    ->label('Customer phone'),
                Forms\Components\TextInput::make('prepared_for_customer_address')
                    ->label('Customer address'),

                RichEditor::make('objective')
                    ->label('Objective')
                    ->columnSpanFull()
                    ->toolbarButtons([
                        'bold',
                        'italic',
                        'underline',
                        'bulletList',
                        'orderedList',
                        'link',
                        'undo',
                        'redo',
                    ])
                    ->disableToolbarButtons([
                        'attachFiles',
                        'codeBlock',
                        'h2',
                        'h3',
                        'strike',
                    ]),
                RichEditor::make('project_description')
                    ->label('Project Scope')
                    ->columnSpanFull()
                    ->toolbarButtons([
                        'bold',
                        'italic',
                        'underline',
                        'bulletList',
                        'orderedList',
                        'link',
                        'undo',
                        'redo',
                    ])
                    ->disableToolbarButtons([
                        'attachFiles',
                        'codeBlock',
                        'h2',
                        'h3',
                        'strike',
                    ]),

                Forms\Components\Textarea::make('proces_briefing')
                    ->columnSpanFull()
                    ->rows(5),
                RichEditor::make('process_details_in_bullets')
                    ->columnSpanFull()
                    ->toolbarButtons([
                        'bold',
                        'italic',
                        'underline',
                        'bulletList',
                        'orderedList',
                        'link',
                        'undo',
                        'redo',
                    ])
                    ->disableToolbarButtons([
                        'attachFiles',
                        'codeBlock',
                        'h2',
                        'h3',
                        'strike',
                    ]),
                RichEditor::make('payment_terms')
                    ->columnSpanFull()
                    ->toolbarButtons([
                        'bold',
                        'italic',
                        'underline',
                        'bulletList',
                        'orderedList',
                        'link',
                        'undo',
                        'redo',
                    ])
                    ->disableToolbarButtons([
                        'attachFiles',
                        'codeBlock',
                        'h2',
                        'h3',
                        'strike',
                    ]),
            ]),
        ];
    }

    /**
     * Reusable schema for pricing/summary fields - NOW WITH RELATIONSHIP!
     */
    public static function pricingSchema(): array
    {
        return [
            Section::make('Pricing Details')
                ->schema([
                    Repeater::make('pricingQuotes')
                        ->relationship('pricingQuotes')
                        ->label('Services List')
                        ->schema([
                            Grid::make(5)->schema([
                                TextInput::make('services')
                                    ->required()
                                    ->label('Service'),

                                    TextInput::make('timeline')
                                    ->required()
                                    ->label('Timeline'),

                                    TextInput::make('quantity')
                                    ->numeric()
                                    ->required()
                                    ->default(1)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        static::updateTotal($set, $get);
                                    }),

                                TextInput::make('unit_price')
                                    ->numeric()
                                    ->required()
                                    ->prefix('$')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        static::updateTotal($set, $get);
                                    }),
                                Placeholder::make('line_total')
                                    ->label('Line Total')
                                    ->content(function ($get) {
                                        $quantity = (float) ($get('quantity') ?: 0);
                                        $unitPrice = (float) ($get('unit_price') ?: 0);
                                        return '$' . number_format($quantity * $unitPrice, 2);
                                    }),
                            ])
                        ])
                        ->defaultItems(1)
                        ->live()
                        ->columnSpanFull()
                        ->addActionLabel('Add Service')
                        ->deleteAction(fn($action) => $action->requiresConfirmation())
                        ->reorderable()
                        ->collapsible()
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            static::updateTotal($set, $get);
                        }),

                    Grid::make(2)->schema([
                        TextInput::make('total_project_price')
                            ->label('Total Project Price')
                            ->prefix('$')
                            ->readOnly()
                            ->reactive()
                            ->dehydrated(true),
                    ]),
                ])
        ];
    }

    // Add this helper method to calculate and update the total
    protected static function updateTotal(callable $set, callable $get): void
    {
        $items = $get('../../pricingQuotes') ?? [];
        $total = 0;

        foreach ($items as $item) {
            $quantity = (float) ($item['quantity'] ?? 0);
            $unitPrice = (float) ($item['unit_price'] ?? 0);
            $total += $quantity * $unitPrice;
        }

        $set('../../total_project_price', number_format($total, 2, '.', ''));
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Customer Details')
                    ->schema(static::detailsSchema())
                    ->collapsible(),

                ...static::pricingSchema(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('proposal_name')
                    ->label('Proposal')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('prepared_for_customer_name')
                    ->label('Client Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('client_company_name')
                    ->label('Company')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('total')
                    ->label('Total Amount')
                    ->getStateUsing(function ($record) {
                        $total = $record->pricingQuotes->sum(function ($quote) {
                            return $quote->quantity * $quote->unit_price;
                        });
                        return '$' . number_format($total, 2);
                    })
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([

                Tables\Actions\ViewAction::make()
                    ->url(fn($record): string => static::getUrl('sign-proposal', ['record' => $record])),
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
            'index' => Pages\ListProposals::route('/'),
            'create' => Pages\CreateProposal::route('/create'),
            'edit' => Pages\EditProposal::route('/{record}/edit'),
            'sign-proposal' => Pages\ProposalBuilder::route('/{record}/sign-proposal'),
        ];
    }
}

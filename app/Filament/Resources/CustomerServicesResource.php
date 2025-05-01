<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerServicesResource\Pages;
use App\Filament\Resources\CustomerServicesResource\RelationManagers;
use App\Models\CustomerServices;
use App\Models\services;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\DatePicker;



class CustomerServicesResource extends Resource
{
    protected static ?string $model = CustomerServices::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function shouldRegisterNavigation(): bool {
        return auth()->user() && auth()->user()->user_role === 'admin' || auth()->user() && auth()->user()->user_role === 'customer';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // service name should be selectable from service model
                Grid::make(2)->schema([
                Select::make('service_id')
                ->label('Select Service')
                ->options(fn() => services::pluck('service_name', 'id'))
                ->searchable()
                ->required()
                ->placeholder('Select Service')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    if ($state) {
                        $services = services::find($state);
                        if ($services) {
                            $set('service_id', $services->id);
                            $set('service_name', $services->service_name);
                        }
                    }
                }),
                Select::make('customer_id')
                ->label('Select Customer')
                ->options(fn() => User::where('user_role', 'customer')->pluck('name', 'id'))
                ->searchable()
                ->required()
                ->placeholder('Select Customer')
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    // where user role is customer
                    if ($state) {
                        $User = User::find($state);
                        if ($User) {
                            $set('customer_id', $User->id);
                        }
                    }
                }),
            ]),
            
                
                TextInput::make('service_name')
                ->label('Service Name')
                ->required()
                ->placeholder('Service Name')
                ->readOnly()
                ->visible(),

                TextInput::make('service_description')
                    ->label('Service Description')
                    ->required()
                    ->placeholder('Service Name'),
                TextInput::make('service_price')
                    ->label('Service Price')
                    ->required()
                    ->placeholder('Service Price'),
                TextInput::make('service_duration')
                    ->label('Service Duration')
                    ->required()
                    ->placeholder('Service Duration'),
                    
                    // status
                Select::make('service_status')
                    ->label('Status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ])
                    ->required()
                    ->placeholder('Select Status'),

                    // start date
                DatePicker::make('start_date')
                    ->label('Start Date')
                    ->required()
                    ->placeholder('Select Start Date')
                    ->default(now())
                    ->maxDate(now()->addYear()),

                DatePicker::make('end_date')
                    ->label('End Date')
                    ->required()
                    ->placeholder('Select End Date')
                    ->default(now())
                    ->maxDate(now()->addYear()),

                    // end date
               
                    // is_recurring
                Select::make('is_recurring')
                    ->label('Is Recurring')
                    ->options([
                        '1' => 'Yes',
                        '0' => 'No',
                    ])
                    ->required()
                    ->placeholder('Select Is Recurring'),

                TextInput::make('recurring_interval')
                    ->label('Recurring Interval')
                    ->required()
                    ->placeholder('Recurring Interval'),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('service_name')
                    ->label('Service Name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('customer_id')
                    ->label('Customer Name')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn ($state) => User::find($state)->name ?? 'N/A'),
                TextColumn::make('service_description')
                    ->label('Service Description')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('service_price')
                    ->label('Service Price')
                    ->sortable()
                    ->prefix('$')
                    ->searchable(),
                TextColumn::make('service_duration')
                    ->label('Service Duration')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('service_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn($state) => $state === 'active' ? 'success' : 'danger')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => ucfirst($state))
                    ->searchable(),
                TextColumn::make('start_date')
                    ->dateTime()
                    ->label('Start Date')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('end_date')
                    ->dateTime()
                    ->label('End Date')
                    ->sortable()
                    ->searchable(),
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
        // Used for what relation? 
        // Answer: It's used for defining the relationships between the CustomerServices resource and other resources.
        return [

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomerServices::route('/'),
            'create' => Pages\CreateCustomerServices::route('/create'),
            'edit' => Pages\EditCustomerServices::route('/{record}/edit'),
        ];
    }
    // public function viewAny(User $user): bool
    // {
    //     // Check if the user is an admin
    //     // return $user->user_role === 'admin' || $user->user_role === 'customer';
    // }
}

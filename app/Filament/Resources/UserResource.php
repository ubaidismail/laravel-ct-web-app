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
use Illuminate\Support\Facades\Date;
use Filament\Forms\Components\DatePicker;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function shouldRegisterNavigation(): bool
    {
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
                        'customer' => 'Customer',
                        'prospect' => 'Prospect',
                        'manager' => 'Manager',
                        'employee' => 'Employee',
                        'tester' => 'Tester',
                    ])
                    ->required()->placeholder('Select User Role')
                    ->live(),

                    Select::make('employee_status')
                    ->options([
                        'active' => 'Active',
                        'probation' => 'Probation',
                        'notice_period' => 'Notice Period',
                        'contract' => 'Contract',
                        'intern' => 'Intern',
                        'trainee' => 'Trainee',
                        'on_leave' => 'On Leave',
                        'unpaid_leave' => 'Unpaid Leave',
                        'laid_off' => 'Laid Off',
                        'contract_ended' => 'Contract Ended',
                        'resigned' => 'Resigned',
                        'terminated' => 'Terminated',
                        'absconded' => 'Absconded',
                        'retired' => 'Retired',
                        'deceased' => 'Deceased',
                        
                    ])
                    ->required()->placeholder('Select Employee Status')
                    ->visible(fn($get) => $get('user_role') === 'employee'),
                    
                DatePicker::make('employment_start_date')->required()
                    ->visible(fn($get) => $get('user_role') === 'employee'),

                DatePicker::make('employment_end_date')
                    ->visible(fn($get) => $get('user_role') === 'employee'),

                TextInput::make('phone')->required()
                    ->maxLength(14),
                TextInput::make('address')->required(),

                TextInput::make('project')->required(fn($get) => $get('user_role') !== 'employee')
                    ->default('N/A')
                    ->disabled(fn($get) => $get('user_role') === 'employee'),
                Select::make('currency')
                    ->options([
                        '$' => 'USD',
                        'PKR' => 'PKR',
                    ])
                    ->default('active')
                    ->required()
                    ->placeholder('Select currency'),
                TextInput::make('company')->required(),
                TextInput::make('pass_for_admin_view')->required(),
                
                TextInput::make('current_salary')->numeric(true)
                    ->hint('Employee only')
                    ->default('0')
                    ->step(0.01)
                    ->placeholder('Enter amount')
                    ->visible(fn($get) => $get('user_role') === 'employee'),   

                TextInput::make('total_amount_paid')->numeric(true)
                    ->default('0')
                    ->step(0.01)
                    ->placeholder('Enter amount paid')
                    ->disabled(fn($get) => $get('user_role') === 'employee'),

                TextInput::make('password')
                    ->required()
                    ->dehydrateStateUsing(fn($state) => bcrypt($state))
                    ->visibleOn(['create', 'edit'])
                    ->password()
                    ->extraFieldWrapperAttributes([
                        'x-data' => '{ show: false }',
                    ])
                    ->extraInputAttributes([
                        'x-bind:type' => 'show ? "text" : "password"',
                    ])
                    ->suffixAction(
                        \Filament\Forms\Components\Actions\Action::make('togglePasswordVisibility')
                            ->icon('heroicon-o-eye')
                            ->extraAttributes([
                                'x-on:click' => 'show = !show',
                            ])
                            ->color('gray')
                    ),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('name')->searchable(),
                TextColumn::make('email')->searchable(),
                TextColumn::make('user_role')
                    ->badge()
                    ->color(function (string $state): string {
                        if ($state === 'admin') {
                            return 'danger';
                        } elseif ($state === 'prospect') {
                            return 'warning';
                        }

                        return 'success';
                    })
                    ->searchable(),
                // TextColumn::make('')->label('Login As')->getStateUsing(function (User $record) {
                //     // return $record->name;
                // }),
                TextColumn::make('created_at')->date()->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make('Edit'),

                Tables\Actions\DeleteAction::make('delete')
                    ->requiresConfirmation()
                    ->action(fn(User $record) => $record->delete())
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

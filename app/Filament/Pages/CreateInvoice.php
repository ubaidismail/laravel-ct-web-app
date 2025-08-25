<?php

namespace App\Filament\Pages;


use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Components\{Grid, TextInput, Select, Repeater, DatePicker, Textarea, Section, Placeholder};
use Illuminate\Support\Facades\DB;
use App\Models\invoices as Invoices; 
use App\Models\User;
use Filament\Forms\Form;
use Filament\Actions\Action;
use Filament\Notifications\Notification;



class CreateInvoice extends Page implements Forms\Contracts\HasForms
{
    // protected static string $resource = InvoiceResource::class;
    // show in sidebar if user role is admin
   
    use Forms\Concerns\InteractsWithForms;

    public $user_id;
    public $client_address;
    public $company_name;
    public $client_phone;
    public $project_name;
    public $client_email;
    public $client_currency;
    public $due_date;
    public $items = [];
    public $tax_rate = 0; // Default tax rate (e.g., 10%)
    public $subtotal = 0;
    public $tax_amount = 0.00;
    public $total = 0;
    public $invoice_type = 0;
    public $invoice_note = '';
    public $amount_in_PKR = 0;



    protected static ?string $navigationIcon = 'heroicon-o-plus-circle';
    protected static ?string $navigationLabel = 'Create Invoice';
    protected static string $view = 'filament.pages.create-invoice';
    protected static ?string $navigationGroup = 'Invoices';
    public static function shouldRegisterNavigation(): bool
    {
        // Check if the user is an admin
        return auth()->user() && auth()->user()->user_role === 'admin';
    }
    

    public function mount(): void
    {
        if (auth()->user()->user_role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $this->form->fill();
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make(2)->schema([
                Select::make('user_id')
                    ->label('Invoice To')
                    ->options(fn() => User::pluck('name', 'id'))
                    ->searchable()
                    ->required()
                    ->placeholder('Select a user')
                    ->live()
                    ->afterStateUpdated(function ($state, $set) {
                        if ($state) {
                            $user = User::find($state);
                            if ($user) {
                                $set('customer_id', $user->id);
                                $set('company_name', $user->company_name ?? $user->name);
                                $set('client_address', $user->address);
                                $set('client_phone', $user->phone);
                                $set('project_name', $user->project);
                                $set('client_email', $user->email);
                                $set('client_currency', $user->currency == '' ? '$': $user->currency);
                            }
                        }
                    }),

                DatePicker::make('due_date')->required(),
            ]),
            Grid::make(2)->schema([
               
                TextInput::make('company_name')
                ->label('Company Name')
                ->disabled()
                ->dehydrated(),

                TextInput::make('client_address')
                    ->label('Address')
                    ->disabled()
                    ->dehydrated(),

                TextInput::make('client_phone')
                    ->label('Phone')
                    ->disabled()
                    ->dehydrated(),

                TextInput::make('client_email')
                    ->label('Email')
                    ->disabled()
                    ->dehydrated(),

                TextInput::make('client_currency')
                    ->label('Currency')
                    ->disabled()
                    ->dehydrated(),
                TextInput::make('project_name')
                    ->label('Project Name')
                    // ->disabled()
                    // ->dehydrated(),
            ]),
            
            Grid::make(2)->schema([
                select::make('invoice_type')
                    ->label('Invoice Type')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'overdue' => 'Overdue',
                        'draft' => 'Draft',
                    ])
                    ->default('pending')
                    ->required()
                    ->placeholder('Select Invoice Type'),
                
            ]),
            
            Repeater::make('items')
            ->label('Invoice Items')
            ->schema([
                Grid::make(4)->schema([
                TextInput::make('description')->required(),
                TextInput::make('quantity')
                    ->numeric()
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn () => $this->calculateTotals()),
                TextInput::make('unit_price')
                    ->numeric()
                    ->required()
                    ->prefix(fn ($get) => $this->client_currency ?: '$')
                    ->live()
                    ->afterStateUpdated(fn () => $this->calculateTotals()),
                Placeholder::make('line_total')
                    ->label('Line Total')
                    ->content(function ($get) {
                        $quantity = (float) $get('quantity') ?: 0;
                        $unitPrice = (float) $get('unit_price') ?: 0;
                        $currency = $this->client_currency ?: '$';
                        return $currency . ' ' . number_format($quantity * $unitPrice, 2);
                    }),

                
            ])
        ])
            ->defaultItems(1)
            ->createItemButtonLabel('Add Item')
            ->live()
            ->afterStateUpdated(fn () => $this->calculateTotals()),
            
        Section::make('Invoice Totals')
            ->schema([
                Grid::make(2)->schema([
                    TextInput::make('tax_rate')
                        ->label('Tax Rate (%)')
                        ->numeric()
                        ->default(0)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn () => $this->calculateTotals()),
                        
                    Placeholder::make('subtotal')
                        ->label('Subtotal')
                        ->content(fn () => ($this->client_currency ?: '$') . ' ' . number_format($this->subtotal, 2)),
                        
                    Placeholder::make('tax_amount')
                        ->label('Tax Amount')
                        ->content(fn () => ($this->client_currency ?: '$') . ' ' . number_format($this->tax_amount, 2)),
                        
                    Placeholder::make('total')
                        ->label('Total')
                        ->content(fn () => ($this->client_currency ?: '$') . ' ' . number_format($this->total, 2))
                        ->extraAttributes(['class' => 'font-bold text-lg']),

                        TextInput::make('amount_in_PKR')
                        ->label('Total Amount in PKR')
                        ->numeric()
                        ->default(fn () => $this->amount_in_PKR)
                        ->afterStateUpdated(fn () => $this->calculateTotals())
                ])
                ]),
            Grid::make(2)->schema([
                Textarea::make('invoice_note')
                    ->label('Invoice Note')
                    ->placeholder('Add comma seperated ponts or comments here')
                    ->rows(3)
                    ->maxLength(500)
            ])
    ];
    }

    public function calculateTotals(): void
    {
        $formData = $this->form->getState();
        $items = $formData['items'] ?? [];
        $taxRate = (float) ($formData['tax_rate'] ?? 0);
        
        $subtotal = 0;
        
        foreach ($items as $item) {
            $quantity = (float) ($item['quantity'] ?? 0);
            $unitPrice = (float) ($item['unit_price'] ?? 0);
            $subtotal += $quantity * $unitPrice;
        }
        
        $this->subtotal = $subtotal;
        $this->tax_amount = $this->subtotal * ($taxRate / 100);
        $this->total = $this->subtotal + $this->tax_amount;
    }

    public function create(): void
    {
        $data = $this->form->getState();
        

        try {
            $invoiceNumber = 'INV-' . date('Ymd') . '-' . rand(1000, 9999);

        $invoice = Invoices::create([
            'inv_no' => $invoiceNumber,
            'author_id' => auth()->id(),
            'customer_id' => $data['user_id'],
            'company_name' => $data['company_name'],
            'due_date' => $data['due_date'],
            'project_name' => $data['project_name'],
            'address' => $data['client_address'],
            'client_phone' => ($data['client_phone'] ? $data['client_phone'] : ''),
            'client_email' => $data['client_email'],
            'client_currency' => $data['client_currency'],
            'sub_total' => $this->subtotal,
            'tax_rate' => $data['tax_rate'],
            'tax_amount' => $this->tax_amount,
            'total_amount' => $this->total,
            'invoice_type' => $data['invoice_type'],
            'amount_in_PKR' => $this->amount_in_PKR,
            'paid_date' => $data['invoice_type'] === 'paid' ? now() : '',
            'inv_notes' => $data['invoice_note'] == '' ? '' : $data['invoice_note'],
        ]);

        // Save invoice items
        foreach ($data['items'] as $item) {
            $quantity = (float) $item['quantity'];
            $unitPrice = (float) $item['unit_price'];
            $lineTotal = $quantity * $unitPrice;
            
            $invoice->items()->create([
                'description' => $item['description'],
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total' => $lineTotal,
            ]);
        }

        Notification::make()
            ->title('Invoice created successfully')
            ->success()
            ->send();
            $this->redirect('/invoices'); // Update this to match your route
        } catch (\Throwable $th) {
            \Log::error('Invoice update failed', [
                'invoice_id' => $this->record->id,
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString()
            ]);

            Notification::make()
            ->title('Failed to update invoice')
            ->body('Please check your input and try again.')
            ->danger()
            ->send();

        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('create')
                ->label('Create Invoice')
                ->action('create')
                ->color('primary'),
        ];
    }
}

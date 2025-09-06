<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use App\Models\InvoiceItem;
use Filament\Resources\Pages\Page;
use Filament\Forms;
use Filament\Forms\Components\{Grid, TextInput, Select, Repeater, DatePicker, Textarea, Section, Placeholder};
use Illuminate\Support\Facades\DB;
use App\Models\invoices as Invoices; 
use App\Models\User;
use Filament\Forms\Form;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Container\Attributes\Log;


class EditInvoice extends Page implements Forms\Contracts\HasForms
{
    protected static string $resource = InvoiceResource::class;

    protected static string $view = 'filament.resources.invoice-resource.pages.edit-invoice';
    use Forms\Concerns\InteractsWithForms;
    
    public $record;
    public $recordItem;
    public $user_id;
    public $client_address;
    public $company_name;
    public $client_phone;
    public $client_email;
    public $project_name;
    public $client_currency;
    public $due_date;
    public $items = [];
    public $tax_rate = 0; // Default tax rate (e.g., 10%)
    public $subtotal = 0;
    public $tax_amount = 0;
    public $total = 0;
    public $paid_date_formatted = null;
    public $invoice_type = 0;
    public $invoice_note = '';
    public $amount_in_PKR = 0;

    public function mount($record): void
    {

        if (auth()->user()->user_role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $this->record = Invoices::findOrFail($record);
        $this->project_name = $this->record->project_name;

        // $this->recordItem = InvoiceItem::where('invoice_id', $this->record->id)->get();
        // var_dump($this->record);
        // Load the invoice data
        $invoiceItems = $this->record->items->map(function($item) {
            return [
                'description' => $item->description,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'total' => $item->total,
            ];
        })->toArray();
        // $invoiceItems = [];
        
        $this->form->fill([
            'user_id' => $this->record->customer_id,
            'company_name' => $this->record->company_name,
            'client_address' => $this->record->address,
            'client_phone' => $this->record->client_phone,
            'project_name' => $this->record->project_name,
            'client_email' => $this->record->client_email,
            'client_currency' => $this->record->client_currency,
            'due_date' => $this->record->due_date,
            'invoice_type' => $this->record->invoice_type,
            'paid_date_formatted' => $this->record->paid_date_formatted,
            'tax_rate' => $this->record->tax_rate,
            'invoice_note' => $this->record->inv_notes,
            'amount_in_PKR' => $this->record->amount_in_PKR,
            'items' => $invoiceItems,
        ]);
        
        $this->calculateTotals();
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
                            $from_invoice = Invoices::find($state);
                            if ($from_invoice) {
                                $set('customer_id', $from_invoice->customer_id);
                                $set('company_name', $from_invoice->company_name ?? $from_invoice->project_name);
                                $set('client_address', $from_invoice->address);
                                $set('client_phone', $from_invoice->client_phone);
                                $set('client_email', $from_invoice->client_email);
                                $set('client_currency', $from_invoice->client_currency ?? 'USD');
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
                    // ->disabled()
                    ->dehydrated(),

                TextInput::make('client_phone')
                    ->label('Phone'),


                TextInput::make('client_email')
                    ->label('Email')
                    // ->disabled()
                    ->dehydrated(),

                TextInput::make('client_currency')
                    ->label('Currency')
                    ->disabled()
                    ->dehydrated(),

                TextInput::make('project_name')
                    ->label('Project Name')
                    ->default(fn () => $this->project_name)  //get value from db

            ]),
            
            Grid::make(2)->schema([
                Select::make('invoice_type')
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

                DatePicker::make('paid_date_formatted')
                    ->label('Paid Date')
                    // required if invoice type is paid
                    ->required(fn ($get) => $get('invoice_type') == 'paid'),
                
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
            // ->createItemButtonLabel('Add Item')
            ->live()
            ->afterStateUpdated(fn () => $this->calculateTotals()),
            
        Section::make('Invoice Totals')
            ->schema([
                Grid::make(2)->schema([
                    TextInput::make('tax_rate')
                        ->label('Tax Rate (%)')
                        // can be decimal or whole number
                        ->numeric()
                        ->step(0.01)
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
                        ->afterStateUpdated(fn () => $this->calculateTotals()),
                ])
            ]),
            Grid::make(2)->schema([
                Textarea::make('invoice_note')
                    ->label('Invoice Note')
                    ->default('')
                    ->placeholder('Add any notes or comments here')
                    ->rows(3)
                    ->maxLength(500)
            ])
        ];
    }

    public function calculateTotals(): void
    {
        $formData = $this->form->getState();
        $items = $formData['items'] ?? [];
        $taxRate = ($formData['tax_rate'] ?? 0);
        
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

    public function update(): void
    {
        $data = $this->form->getState();
        try {
            $this->record->update([
                'customer_id' => $data['user_id'],
                'company_name' => $data['company_name'],
                'due_date' => $data['due_date'],
                'address' => $data['client_address'],
                'client_phone' => $data['client_phone'],
                'client_email' => $data['client_email'],
                'project_name' => $data['project_name'],
                'client_currency' => $data['client_currency'],
                'sub_total' => $this->subtotal,
                'tax_rate' => $data['tax_rate'],
                'tax_amount' => $this->tax_amount,
                'total_amount' => $this->total,
                'invoice_type' => $data['invoice_type'],
                'paid_date_formatted' => $data['invoice_type'] == 'paid' ? $data['paid_date_formatted'] : null,
                'inv_notes' => $data['invoice_note'] ?? '',
                'amount_in_PKR' => $data['amount_in_PKR'], // Assuming 1 USD = 280 PKR
            ]);
    
            // Delete old items and save new ones
            $this->record->items()->delete();
            
            foreach ($data['items'] as $item) {
                $quantity = (float) $item['quantity'];
                $unitPrice = (float) $item['unit_price'];
                $lineTotal = $quantity * $unitPrice;
                
                $this->record->items()->create([
                    'description' => $item['description'],
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total' => $lineTotal,
                ]);
            }
            Notification::make()
            ->title('Invoice updated successfully')
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
            ->title('Invoice Update Failed!')
            ->danger()
            ->send();
        }

    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('update')
                ->label('Update Invoice')
                ->action('update')
                ->color('primary'),
        ];
    }
    
    // Add the static route method to make it compatible with resource pages
    // public static function route(string $path): array
    // {
    //     return [
    //         'slug' => $path,
    //     ];
    // }
    
}
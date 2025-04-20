<?php

namespace App\Console\Commands;

use App\Models\InvoiceItem;
use App\Models\invoices;

use Illuminate\Console\Command;

// in this file i have to migrate the data from invoices table to invoice_items table the data was 
// in not in a json format and have the quotes and brackets in the data so we used the explode and trim 
// to remove the quotes and brackets from the data and then insert the data into the invoice_items table

class MigrateInvoiceItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:invoice-items';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate invoice data to invoice_items table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $invoices = Invoices::all();
        foreach ($invoices as $invoice) {
            // Assuming you have description, qty, price, and total in your invoices table


            $descriptions = array_map('trim', explode(',', $invoice->description));
            $quantity = array_map('trim', explode(',', $invoice->quantity));
            $unit_price = array_map('trim', explode(',', $invoice->unit_price));
            $total = array_map('trim', explode(',', $invoice->total));
            
            $count = count($descriptions);
            

            // Insert the data into invoice_items table
            for ($i = 0; $i < $count; $i++) {
                $clean_description = trim($descriptions[$i], '[]"');
                $clean_quantity = trim($quantity[$i], '[]"');
                $clean_unit_price = trim($unit_price[$i], '[]"');
                $clean_total = trim($total[$i], '[]"');


                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'description' => $clean_description,
                    'quantity'    => $clean_quantity,
                    'unit_price'  => $clean_unit_price,
                    'total'       => $clean_total,
                ]);
            }
        }
        $this->info('Invoice items migrated successfully.');
        // Optionally, you can delete the old data from the invoices table if needed

    }
}

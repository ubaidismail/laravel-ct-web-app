<?php

namespace App\Http\Controllers;

use App\Models\invoices;
use Illuminate\Http\Request;
use App\Models\InvoiceItem;
use Barryvdh\DomPDF\Facade\Pdf;


class InvoiceController extends Controller
{
    public function download($invoice_id)
    {
        // Fetch the invoice and its items from the database
        $invoice = Invoices::with('items')->findOrFail($invoice_id);

        // // Generate the PDF using a view
        $pdf = Pdf::loadView('pdf.invoice', compact('invoice'));
        // $pdf = Pdf::loadHTML('<span>Ubaid</span>', ['invoice' => $invoice]);
      
        return $pdf->download("invoice-$invoice->company_name #$invoice_id.pdf");

        
    }


}

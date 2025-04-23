<?php

namespace App\Livewire;

use App\Models\invoices;
use Filament\Widgets\Widget;


class MyInvoices extends Widget
{
    protected static string $view = 'livewire.my-invoices';

    public function getViewData(): array
    {
        $userId = auth()->id();

        $invoices = invoices::where('customer_id', $userId)
            ->latest()
            // ->limit(1)
            ->get();

        return [
            'invoices' => $invoices,
            'invoiceCount' => $invoices->count(),
        ];
    }
}

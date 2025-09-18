<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class invoices extends Model
{
    protected $fillable = [
        'inv_no',
        'author_id',
        'customer_id',
        'company_name',
        'project_name',
        'address',
        'client_phone',
        'client_email',
        'sub_total',
        'tax_rate',
        'tax_amount',
        'total_amount',
        'client_currency',
        'invoice_type',
        'paid_date_formatted',
        'due_date',
        'inv_notes',
        'status',
        'amount_in_PKR',
    ];

    protected $casts = [
        'paid_date_formatted' => 'datetime',
        'total_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'sub_total' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'status' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function items()
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id');
    }
    public function getPaidDateAttribute($value)
    {
        return $value ? \Carbon\Carbon::parse($value)->format('Y-m-d') : null;
    }

    public function duplicate()
    {
        // Create a new invoice with the same data
        $duplicate = $this->replicate();
        
        // Generate a new invoice number
        $duplicate->inv_no = 'INV-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        // Reset status and dates
        $duplicate->status = '1';
        $duplicate->invoice_type = 'draft';
        $duplicate->paid_date_formatted = null;
        $duplicate->due_date = date('Y-m-d', strtotime('+30 days'));
        
        // Save the duplicate
        $duplicate->save();
        
        // Duplicate invoice items if they exist
        if ($this->items()->count() > 0) {
            foreach ($this->items as $item) {
                $duplicateItem = $item->replicate();
                $duplicateItem->invoice_id = $duplicate->id;
                $duplicateItem->save();
            }
        }
        
        return $duplicate;
    }

}

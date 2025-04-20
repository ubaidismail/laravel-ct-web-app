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

    public function user()
    {
        return $this->belongsTo(Users::class);
    }
    public function items()
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id');
    }
    public function getPaidDateAttribute($value)
    {
        return $value ? \Carbon\Carbon::parse($value)->format('Y-m-d') : null;
    }

}

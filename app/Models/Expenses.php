<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expenses extends Model
{
    protected $fillable = [
        'expense_type',
        'expense_name',
        'amount',
        'expense_date',
        'is_recurring',
        'recurrence_pattern',
        'next_occurrence',
        'receipt_url',
        'notes',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanySalesTargets extends Model
{
    protected $fillable = [
        'company_name',
        'sales_target',
        'period',
    ];

    protected $casts = [
        'period' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}

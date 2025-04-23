<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerServices extends Model
{
    protected $fillable = [
        'customer_id',
        'service_id',
        'service_name',
        'service_description',
        'service_price',
        'service_duration',
        'service_status',
        'start_date',
        'end_date',
        'is_recurring',
        'recurring_interval',
    ];

    public function user()
    {
        return $this->belongsTo(Users::class, 'customer_id');
    }
    public function service()
    {
        return $this->belongsTo(services::class, 'service_id');
    }

}


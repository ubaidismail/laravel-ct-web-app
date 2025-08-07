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
        return $this->belongsTo(User::class, 'customer_id');
    }
    // In your app/Models/CustomerServices.php file
   
    public function service()
    {
        return $this->belongsTo(services::class, 'service_id');
    }
    // Check if service is expiring soon (within 7 days)
    public function isExpiringSoon()
    {
        return Carbon::today()->diffInDays($this->end_date, false) <= 7 &&
            $this->end_date >= Carbon::today();
    }

    // Get days until expiry
    public function getDaysUntilExpiry()
    {
        return Carbon::today()->diffInDays($this->end_date, false);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CronJobs extends Model
{
    protected $fillable = [
        'job_name',
        'status',
        'sender_email',
        'last_run_time',
        'failed_reason',
    ];

    
    
}

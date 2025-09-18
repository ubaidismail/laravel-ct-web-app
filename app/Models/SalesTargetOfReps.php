<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesTargetOfReps extends Model
{
    protected $fillable = [
        'rep_id',
        'rep_name',
        'target',
        'mtd_sales',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}

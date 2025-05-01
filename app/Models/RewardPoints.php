<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class RewardPoints extends Model
{
    protected $fillable = [
        'project_quote_id',
        'referrer_user_id',
        'token_id',
        'amount',
        'referal_type',
        'status',
        'percent_markup',
        'issued_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

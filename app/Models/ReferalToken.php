<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferalToken extends Model
{
    protected $fillable = ['token', 'referrer_user_id', 'expires_at'];

    public function user()
    {
        return $this->belongsTo(Users::class, 'referrer_user_id');
    }
    protected function isValid($token)
    {
        return $this->where('token', $token)
            ->exists();
    }
    // public function scopeValid($query)
    // {

    //     return $query->where('expires_at', '>', now());
    // }
    
}

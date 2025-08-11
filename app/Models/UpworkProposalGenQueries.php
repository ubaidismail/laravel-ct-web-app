<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UpworkProposalGenQueries extends Model
{
    protected $fillable = [
        'user_id',
        'project_description',
        'portfolio',
        'AI_result',
        'conversion_rate',
        'AI_model',
        'error',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

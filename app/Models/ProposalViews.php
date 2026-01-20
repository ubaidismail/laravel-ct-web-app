<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProposalViews extends Model
{
    protected $fillable = ['proposal_id',
    'page_url', 
    'ip_hash', 
    'browser', 
    'user_agent',
    'viewed_at'
   ];
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Proposals extends Model
{
    protected $fillable = ['prepared_for_customer_name',
     'proposal_name', 
     'prepared_for_customer_email', 
     'client_company_name', 
     'prepared_for_customer_phone',
     'prepared_for_customer_address',
     'proces_briefing',
     'process_details_in_bullets',
     'project_description',
     'payment_terms',
     'total_project_price',
     'client_signature',
     'date_signed',
    ];
    protected $casts = [
        'date_signed' => 'datetime',
    ];
    public function pricingQuotes(): HasMany
    {
        return $this->hasMany(ProposalPricingQuote::class, 'proposal_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProposalVersions extends Model
{
    protected $fillable = ['prepared_for_customer_name',
    'proposal_id',
     'proposal_name', 
     'version_number',
     'prepared_for_customer_email', 
     'client_company_name', 
     'prepared_for_customer_phone',
     'prepared_for_customer_address',
     'proces_briefing',
     'process_details_in_bullets',
     'objective',
     'project_description',
     'payment_terms',
     'total_project_price',
     'client_signature',
     'date_signed',
     'proposal_type',
     'send_as',
     'change_summary',
     'changed_by',
    ];
    protected $casts = [
        'date_signed' => 'datetime',
    ];
    public function pricingQuotes(): HasMany
    {
        return $this->hasMany(ProposalPricingQuote::class, 'proposal_id');
    }
}

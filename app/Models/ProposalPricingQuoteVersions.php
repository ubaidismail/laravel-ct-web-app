<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProposalPricingQuoteVersions extends Model
{
    protected $table = 'proposal_pricing_quote_versions';
    
    protected $fillable = [
        'proposal_version_id',
        'version_number',
        'services',
        'timeline',
        'quantity',
        'unit_price',
        'total',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(ProposalVersions::class, 'proposal_version_id');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProposalPricingQuote extends Model
{
    protected $table = 'proposal_pricing_quote';
    
    protected $fillable = [
        'proposal_id',
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
        return $this->belongsTo(Proposals::class, 'proposal_id');
    }
}
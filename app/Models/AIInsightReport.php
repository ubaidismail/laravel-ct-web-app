<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AIInsightReport extends Model
{
    use HasFactory;
    protected $table = 'AI_insight_reports';
    protected $fillable = [
        'type',
        'data',
        'generated_at',
        'user_id',
        'status'
    ];

    protected $casts = [
        'data' => 'array',
        'generated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getTypeDisplayAttribute(): string
    {
        return match($this->type) {
            'revenue_analysis' => 'Revenue Analysis',
            'service_performance' => 'Service Performance',
            'customer_insights' => 'Customer Insights',
            'forecast' => 'Revenue Forecast',
            default => ucfirst(str_replace('_', ' ', $this->type))
        };
    }
}
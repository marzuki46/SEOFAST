<?php

namespace App\Models;

use App\Models\Traits\TenantAwareTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GscSearchAnalytics extends Model
{
    use TenantAwareTrait;

    protected $fillable = [
        'tenant_id',
        'content_id',
        'query',
        'page_url',
        'country',
        'device',
        'data_date',
        'clicks',
        'impressions',
        'ctr',
        'position',
        'synced_at',
    ];

    protected function casts(): array
    {
        return [
            'clicks' => 'integer',
            'impressions' => 'integer',
            'ctr' => 'decimal:6',
            'position' => 'decimal:2',
            'data_date' => 'date',
            'synced_at' => 'datetime',
        ];
    }

    protected $table = 'gsc_search_analytics';

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function content(): BelongsTo
    {
        return $this->belongsTo(Content::class);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('data_date', [$startDate, $endDate]);
    }

    public function scopeByQuery($query, string $searchQuery)
    {
        return $query->where('query', $searchQuery);
    }
}
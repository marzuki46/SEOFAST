<?php

namespace App\Models;

use App\Models\Traits\TenantAwareTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GscSyncLog extends Model
{
    use TenantAwareTrait;

    protected $fillable = [
        'tenant_id',
        'sync_type',
        'status',
        'total_urls',
        'processed_urls',
        'failed_urls',
        'error_summary',
        'api_quota_used',
        'duration_seconds',
        'started_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'total_urls' => 'integer',
            'processed_urls' => 'integer',
            'failed_urls' => 'integer',
            'error_summary' => 'array',
            'api_quota_used' => 'integer',
            'duration_seconds' => 'decimal:2',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function urlInspections(): HasMany
    {
        return $this->hasMany(GscUrlInspection::class);
    }
}

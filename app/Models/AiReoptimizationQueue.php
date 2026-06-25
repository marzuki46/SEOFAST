<?php

namespace App\Models;

use App\Models\Traits\TenantAwareTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiReoptimizationQueue extends Model
{
    use TenantAwareTrait;

    protected $fillable = [
        'tenant_id',
        'content_id',
        'trigger_reason',
        'position_before',
        'position_after',
        'optimization_directives',
        'status',
        'priority',
        'scheduled_at',
        'processed_at',
    ];

    protected function casts(): array
    {
        return [
            'optimization_directives' => 'json',
            'position_before' => 'integer',
            'position_after' => 'integer',
            'priority' => 'integer',
            'scheduled_at' => 'datetime',
            'processed_at' => 'datetime',
        ];
    }

    protected $table = 'ai_reoptimization_queue';

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function content(): BelongsTo
    {
        return $this->belongsTo(Content::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeByPriority($query)
    {
        return $query->orderBy('priority', 'desc');
    }
}
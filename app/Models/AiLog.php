<?php

namespace App\Models;

use App\Models\Traits\TenantAwareTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AiLog extends Model
{
    use TenantAwareTrait;

    protected $fillable = [
        'tenant_id',
        'provider',
        'model',
        'endpoint',
        'prompt_tokens',
        'completion_tokens',
        'total_tokens',
        'cost_micros',
        'status',
        'error_message',
        'loggable_type',
        'loggable_id',
        'started_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'prompt_tokens' => 'integer',
            'completion_tokens' => 'integer',
            'total_tokens' => 'integer',
            'cost_micros' => 'integer',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    protected $table = 'ai_logs';

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function loggable(): MorphTo
    {
        return $this->morphTo();
    }
}
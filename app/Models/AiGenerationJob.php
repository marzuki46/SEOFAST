<?php

namespace App\Models;

use App\Models\Traits\TenantAwareTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiGenerationJob extends Model
{
    use TenantAwareTrait;

    protected $fillable = [
        'tenant_id',
        'content_id',
        'job_type',
        'status',
        'phase_1_lsi',
        'phase_1_draft',
        'phase_2_critique',
        'phase_3_expanded',
        'phase_4_answers',
        'phase_4_final',
        'phase_5_combined',
        'phase_6_html',
        'tokens_used',
        'llm_model_used',
        'generation_cost_usd',
        'error_log',
        'retry_count',
        'started_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'phase_2_critique' => 'json',
            'error_log' => 'json',
            'tokens_used' => 'integer',
            'generation_cost_usd' => 'decimal:6',
            'retry_count' => 'integer',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    protected $table = 'ai_generation_jobs';

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function content(): BelongsTo
    {
        return $this->belongsTo(Content::class);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
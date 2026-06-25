<?php

namespace App\Models;

use App\Models\Traits\TenantAwareTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CanonicalMapping extends Model
{
    use TenantAwareTrait;

    protected $fillable = [
        'tenant_id',
        'content_id',
        'canonical_target_id',
        'reason',
        'similarity_score',
        'is_resolved',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'similarity_score' => 'decimal:4',
            'is_resolved' => 'boolean',
            'resolved_at' => 'datetime',
        ];
    }

    protected $table = 'canonical_mappings';

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function content(): BelongsTo
    {
        return $this->belongsTo(Content::class);
    }

    public function canonicalTarget(): BelongsTo
    {
        return $this->belongsTo(Content::class, 'canonical_target_id');
    }
}
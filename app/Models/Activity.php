<?php

namespace App\Models;

use App\Models\Traits\TenantAwareTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Activity extends Model
{
    use TenantAwareTrait;

    protected $fillable = [
        'tenant_id',
        'causer_type',
        'causer_id',
        'subject_type',
        'subject_id',
        'action',
        'description',
        'changes',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'changes' => 'json',
        ];
    }

    protected $table = 'activities';

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function causer(): MorphTo
    {
        return $this->morphTo();
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeByAction($query, string $action)
    {
        return $query->where('action', $action);
    }
}
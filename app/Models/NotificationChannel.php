<?php

namespace App\Models;

use App\Models\Traits\TenantAwareTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationChannel extends Model
{
    use TenantAwareTrait;

    protected $fillable = [
        'tenant_id',
        'channel_type',
        'label',
        'config_json',
        'is_active',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'config_json' => 'json',
            'is_active' => 'boolean',
            'verified_at' => 'datetime',
        ];
    }

    protected $table = 'notification_channels';

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('channel_type', $type);
    }
}
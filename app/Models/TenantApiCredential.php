<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TenantApiCredential extends Model
{
    protected $fillable = [
        'tenant_id',
        'service',
        'access_token',
        'refresh_token',
        'service_account_json',
        'property_url',
        'token_expires_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'token_expires_at' => 'datetime',
            'is_active' => 'boolean',
            'service_account_json' => 'encrypted',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function isExpired(): bool
    {
        return $this->token_expires_at && $this->token_expires_at->isPast();
    }

    public function scopeForService($query, string $service)
    {
        return $query->where('service', $service)->where('is_active', true);
    }
}
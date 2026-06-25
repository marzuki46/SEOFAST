<?php

namespace App\Models\Traits;

use App\Models\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Builder;

/**
 * TenantAwareTrait — kept for compatibility but tenant isolation is DISABLED.
 * All data is globally visible to the Super Admin.
 */
trait TenantAwareTrait
{
    protected static function bootTenantAwareTrait(): void
    {
        // Tenant scope is disabled — no isolation applied.
        static::addGlobalScope(new TenantScope());
    }

    public function scopeForCurrentTenant(Builder $query): Builder
    {
        return $query; // No filtering — all data is global
    }

    public function getTenantId(): ?int
    {
        return null;
    }
}
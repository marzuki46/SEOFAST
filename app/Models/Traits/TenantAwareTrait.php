<?php

namespace App\Models\Traits;

use App\Models\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Builder;

/**
 * Applies tenant isolation for tenant-bound users while preserving public reads
 * and the global super-admin view used by this single-site CMS.
 */
trait TenantAwareTrait
{
    protected static function bootTenantAwareTrait(): void
    {
        static::addGlobalScope(new TenantScope);

        static::creating(function ($model): void {
            $tenantId = auth()->user()?->tenant_id;
            if ($tenantId !== null && empty($model->tenant_id)) {
                $model->tenant_id = $tenantId;
            }
        });
    }

    public function scopeForCurrentTenant(Builder $query): Builder
    {
        $tenantId = auth()->user()?->tenant_id;

        return $tenantId === null
            ? $query
            : $query->where($query->getModel()->qualifyColumn('tenant_id'), $tenantId);
    }

    public function getTenantId(): ?int
    {
        $tenantId = $this->getAttribute('tenant_id');

        return $tenantId === null ? null : (int) $tenantId;
    }
}

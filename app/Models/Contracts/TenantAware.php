<?php

namespace App\Models\Contracts;

interface TenantAware
{
    /**
     * Get the tenant ID associated with this model.
     */
    public function getTenantId(): ?int;

    /**
     * Scope the query to the current tenant.
     */
    public function scopeForCurrentTenant($query);
}
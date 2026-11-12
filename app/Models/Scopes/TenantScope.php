<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TenantScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $tenantId = auth()->user()?->tenant_id;

        // Public content and the super-admin remain global. A tenant-bound account
        // can never read rows belonging to another tenant.
        if ($tenantId !== null && $model->getTable() !== 'tenants') {
            $builder->where($model->qualifyColumn('tenant_id'), $tenantId);
        }
    }
}

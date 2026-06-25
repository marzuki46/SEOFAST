<?php

if (!function_exists('system_setting')) {
    /**
     * Get a global system setting value.
     */
    function system_setting(string $key, mixed $default = null): mixed
    {
        return \App\Models\SystemSetting::get($key, $default);
    }
}

if (!function_exists('tenant_setting')) {
    /**
     * Get a setting for the current authenticated tenant.
     */
    function tenant_setting(string $key, mixed $default = null): mixed
    {
        $tenantId = auth()->user()?->tenant_id;
        if (!$tenantId) return $default;

        return \Illuminate\Support\Facades\Cache::remember(
            "tenant_{$tenantId}_setting_{$key}",
            3600,
            fn() => \App\Models\TenantSetting::where('tenant_id', $tenantId)
                ->where('key', $key)
                ->value('value') ?? $default
        );
    }
}

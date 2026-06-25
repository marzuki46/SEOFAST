<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\RateLimiter::for('gsc-url-inspection', function (object $job) {
            // Max 60 inspections per minute per tenant
            return \Illuminate\Cache\RateLimiting\Limit::perMinute(60)->by($job->tenantId);
        });
    }
}

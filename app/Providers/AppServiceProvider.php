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
        error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
        
        \Illuminate\Pagination\Paginator::useTailwind();

        \Illuminate\Support\Facades\RateLimiter::for('gsc-url-inspection', function (object $job) {
            // Max 60 inspections per minute per tenant
            return \Illuminate\Cache\RateLimiting\Limit::perMinute(60)->by($job->tenantId);
        });

        if (config('app.env') === 'production' || str_contains(config('app.url'), 'https://') || request()->header('x-forwarded-proto') === 'https' || (isset($_SERVER['HTTP_HOST']) && str_contains($_SERVER['HTTP_HOST'], 'juki.eu.org'))) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}

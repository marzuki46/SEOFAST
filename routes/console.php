<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\Gsc\SyncUrlInspectionJob;
use App\Jobs\Gsc\SyncSearchAnalyticsJob;
use App\Jobs\Seo\ProcessSerpSnapshotsJob;
use App\Jobs\Seo\ContentFreshnessJob;
use App\Jobs\Seo\CrawlPriorityRecalculateJob;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Sync URL Inspection — setiap hari pukul 03:00 per tenant
Schedule::call(function () {
    \App\Models\TenantApiCredential::where('service', 'google_search_console')
        ->where('is_active', true)
        ->get()
        ->each(function ($cred) {
            SyncUrlInspectionJob::dispatch($cred->tenant_id)
                ->onQueue('gsc-sync');
        });
})->dailyAt('03:00');

// Sync Search Analytics — setiap hari pukul 04:00 (setelah URL inspection selesai)
Schedule::call(function () {
    \App\Models\TenantApiCredential::where('service', 'google_search_console')
        ->where('is_active', true)
        ->get()
        ->each(fn ($cred) => SyncSearchAnalyticsJob::dispatch($cred->tenant_id)->onQueue('gsc-sync'));
})->dailyAt('04:00');

// SERP Snapshot — setiap hari pukul 06:00
Schedule::job(ProcessSerpSnapshotsJob::class, 'serp-tracking')->dailyAt('06:00');

// Re-optimization Queue — setiap jam (proses antrian yang sudah scheduled)
Schedule::call(function () {
    \App\Models\AiReoptimizationQueue::where('status', 'pending')
        ->where('scheduled_at', '<=', now())
        ->orderByDesc('priority')
        ->limit(5) // Batasi per jam sesuai quota LLM
        ->get()
        ->each(fn ($item) => \App\Jobs\Ai\ProcessReoptimizationJob::dispatch($item)->onQueue('ai-heavy'));
})->hourly();

// Content Freshness Engine — setiap hari Senin pukul 02:00
Schedule::job(ContentFreshnessJob::class, 'ai-heavy')->weeklyOn(1, '02:00');

// Crawl Priority Recalculate — setiap hari pukul 01:00
Schedule::job(CrawlPriorityRecalculateJob::class, 'maintenance')->dailyAt('01:00');

// Purge bot logs lama (> 90 hari) — setiap minggu
Schedule::call(function () {
    if (\Illuminate\Support\Facades\Schema::hasTable('seo_bot_logs')) {
        \DB::table('seo_bot_logs')
            ->where('crawled_at', '<', now()->subDays(90))
            ->delete();
    }
})->weekly();

// Horizon snapshot — setiap 5 menit (biar grafik metrics terisi)
if (config('horizon.defaults')) {
    Schedule::command('horizon:snapshot')->everyFiveMinutes();
}

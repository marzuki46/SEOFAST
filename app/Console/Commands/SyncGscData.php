<?php

namespace App\Console\Commands;

use App\Models\Content;
use App\Models\GscSearchAnalytics;
use App\Models\Tenant;
use App\Models\TenantApiCredential;
use App\Services\Gsc\GoogleSearchConsoleService;
use Illuminate\Console\Command;

class SyncGscData extends Command
{
    protected $signature = 'seofast:sync-gsc
        {--sample : Generate sample GSC data for demo/testing}
        {--tenant= : Tenant ID (default: first tenant)}
        {--days=7 : Days of sample data to generate}';

    protected $description = 'Sync GSC Search Analytics data or generate sample data';

    public function handle(): int
    {
        $tenantId = (int) ($this->option('tenant') ?: Tenant::first()?->id);
        if (!$tenantId) {
            $this->error('No tenant found. Create a tenant first.');
            return Command::FAILURE;
        }

        if ($this->option('sample')) {
            return $this->generateSampleData($tenantId);
        }

        $credential = TenantApiCredential::where('tenant_id', $tenantId)
            ->where('service', 'google_search_console')
            ->where('is_active', true)
            ->first();

        if (!$credential) {
            $this->error('No active GSC credentials found for tenant ' . $tenantId . '.');
            $this->warn('Use --sample to generate sample data, or connect GSC via the admin UI.');
            return Command::FAILURE;
        }

        $this->info('Syncing GSC Search Analytics...');
        $service = new GoogleSearchConsoleService($tenantId);

        $endDate = now()->subDays(4)->format('Y-m-d');
        $startDate = now()->subDays(10)->format('Y-m-d');

        $this->line("  Date range: {$startDate} → {$endDate}");

        try {
            $result = $service->fetchSearchAnalytics($startDate, $endDate, $tenantId);

            $this->info("  Total rows from API:  {$result['total_rows']}");
            $this->info("  Saved to database:    {$result['saved_rows']}");
            $this->info("  Skipped (no slug):    {$result['skipped_rows']}");

            $this->newLine();
            $this->info('GSC sync completed successfully!');
        } catch (\Exception $e) {
            $this->error('GSC sync failed: ' . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    protected function generateSampleData(int $tenantId): int
    {
        $days = (int) $this->option('days');
        $this->info("Generating {$days} days of sample GSC data for tenant {$tenantId}...");

        $contents = Content::whereIn('status', ['published', 'draft'])
            ->whereNotNull('slug')
            ->get();

        if ($contents->isEmpty()) {
            $this->error('No content found. Create some content first.');
            return Command::FAILURE;
        }

        $devices = ['desktop', 'mobile', 'tablet'];
        $countries = ['id', 'us', 'sg', 'my'];
        $bar = $this->output->createProgressBar($contents->count() * $days);
        $bar->start();

        $inserted = 0;
        $queries = [];

        foreach ($contents as $content) {
            $kw = $content->target_keyword ?: str_replace('-', ' ', $content->slug);
            // Generate related queries
            $baseQueries = [
                $kw,
                $kw . ' terbaru',
                $kw . ' tutorial',
                $kw . ' 2026',
                'cara ' . $kw,
                'apa itu ' . $kw,
                'rekomendasi ' . $kw,
                'harga ' . $kw,
            ];
            $queries[$content->id] = array_slice($baseQueries, 0, rand(3, 5));

            for ($d = $days; $d >= 0; $d--) {
                $date = now()->subDays($d)->format('Y-m-d');

                foreach ($queries[$content->id] as $query) {
                    $position = max(1, round(15 - ($d * 0.3) + (rand(-3, 3))));
                    $impressions = max(10, round(200 - ($d * 2) + rand(-20, 20)));
                    $ctr = max(0.01, round((rand(2, 8) / 100), 4));
                    $clicks = max(1, round($impressions * $ctr));

                    $upsertData = [
                        'tenant_id'   => $tenantId,
                        'content_id'  => $content->id,
                        'query'       => $query,
                        'page_url'    => "https://example.com/{$content->slug}",
                        'country'     => $countries[array_rand($countries)],
                        'device'      => $devices[array_rand($devices)],
                        'data_date'   => $date,
                        'clicks'      => $clicks,
                        'impressions' => $impressions,
                        'ctr'         => $ctr,
                        'position'    => $position,
                        'synced_at'   => now(),
                    ];

                    GscSearchAnalytics::upsert(
                        $upsertData,
                        ['tenant_id', 'content_id', 'query', 'country', 'device', 'data_date'],
                        ['clicks', 'impressions', 'ctr', 'position', 'synced_at']
                    );
                    $inserted++;
                }
                $bar->advance();
            }
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Generated {$inserted} GSC analytics rows across " . $contents->count() . " content items.");
        $this->warn('This is SAMPLE data for testing. Replace with real GSC data when credentials are configured.');

        return Command::SUCCESS;
    }
}

<?php

namespace App\Jobs\Gsc;

use App\Models\Content;
use App\Models\GscSyncLog;
use App\Services\Gsc\GoogleSearchConsoleService;
use App\Services\Seo\RankingFeedbackService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\Middleware\RateLimited;

class SyncUrlInspectionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries    = 3;
    public int $timeout  = 3600; // 1 jam

    public function __construct(
        public readonly int $tenantId,
        public readonly int $batchSize = 50
    ) {}

    public function middleware(): array
    {
        return [new RateLimited('gsc-url-inspection')];
    }

    public function handle(
        GoogleSearchConsoleService $gscService,
        RankingFeedbackService $feedbackService
    ): void {
        $syncLog = GscSyncLog::create([
            'tenant_id'  => $this->tenantId,
            'sync_type'  => 'url_inspection',
            'status'     => 'running',
            'started_at' => now(),
        ]);

        $contents = Content::where('tenant_id', $this->tenantId)
            ->where('status', 'published')
            ->where(function ($query) {
                $query
                    ->whereNull('gsc_coverage_state')
                    ->orWhere('gsc_coverage_state', '!=', 'Submitted and indexed')
                    ->orWhereHas('urlInspections', function ($q) {
                        $q->where('inspected_at', '<', now()->subDays(7));
                    });
            })
            ->orderByRaw("
                CASE 
                    WHEN gsc_coverage_state IS NULL THEN 1
                    WHEN gsc_coverage_state != 'Submitted and indexed' THEN 2
                    ELSE 3
                END
            ")
            ->limit($this->batchSize)
            ->get();

        $processed = 0;
        $failed    = 0;

        foreach ($contents as $content) {
            try {
                $inspection = $gscService->inspectUrl($content, $syncLog);

                $content->update([
                    'gsc_coverage_state' => $inspection->coverage_state,
                ]);

                $feedbackService->processInspectionResult($content, $inspection);

                $processed++;
            } catch (\Exception $e) {
                $failed++;
                \Log::error("GSC inspection failed for content {$content->id}: " . $e->getMessage());
            }

            sleep(1);
        }

        $syncLog->update([
            'status'          => $failed > 0 && $processed === 0 ? 'failed' : 
                                 ($failed > 0 ? 'partial' : 'completed'),
            'total_urls'      => $contents->count(),
            'processed_urls'  => $processed,
            'failed_urls'     => $failed,
            'completed_at'    => now(),
            'duration_seconds'=> now()->diffInSeconds($syncLog->started_at),
        ]);
    }
}

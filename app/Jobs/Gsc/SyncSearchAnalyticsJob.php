<?php

namespace App\Jobs\Gsc;

use App\Models\GscSyncLog;
use App\Services\Gsc\GoogleSearchConsoleService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncSearchAnalyticsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries   = 3;
    public int $timeout = 7200;

    public function __construct(public readonly int $tenantId) {}

    public function handle(GoogleSearchConsoleService $gscService): void
    {
        $syncLog = GscSyncLog::create([
            'tenant_id'  => $this->tenantId,
            'sync_type'  => 'search_analytics',
            'status'     => 'running',
            'started_at' => now(),
        ]);

        try {
            // GSC has a 3-4 day delay, query from 4 days ago to 10 days ago (7-day window)
            $endDate   = now()->subDays(4)->format('Y-m-d');
            $startDate = now()->subDays(10)->format('Y-m-d');

            $result = $gscService->fetchSearchAnalytics($startDate, $endDate, $this->tenantId);

            $syncLog->update([
                'status'           => 'completed',
                'processed_urls'   => $result['saved_rows'],
                'completed_at'     => now(),
                'duration_seconds' => now()->diffInSeconds($syncLog->started_at),
            ]);

            // === FASE 6: Auto Re-optimasi Closed-Loop ===
            // Check for any published content that dropped > 5 positions in last 30 days
            $this->checkAndQueueReoptimization();

        } catch (\Exception $e) {
            $syncLog->update([
                'status'           => 'failed',
                'error_summary'    => ['error' => $e->getMessage()],
                'completed_at'     => now(),
                'duration_seconds' => now()->diffInSeconds($syncLog->started_at),
            ]);
            \Log::error("GSC search analytics sync failed for tenant {$this->tenantId}: " . $e->getMessage());
        }
    }

    /**
     * Check for ranking drops and queue re-optimization for affected content.
     * Spec: If position drops > 5 places in 30 days → add to ai_reoptimization_queue
     */
    private function checkAndQueueReoptimization(): void
    {
        $contents = \App\Models\Content::withoutGlobalScopes()
            ->where('tenant_id', $this->tenantId)
            ->where('status', 'published')
            ->whereNotNull('current_serp_position')
            ->get();

        foreach ($contents as $content) {
            // Get position from 30 days ago vs current
            $positionNow = $content->current_serp_position;
            
            $analytics30DaysAgo = \App\Models\GscSearchAnalytics::where('content_id', $content->id)
                ->where('date_range_start', '<=', now()->subDays(30)->toDateString())
                ->orderBy('date_range_start', 'desc')
                ->first();

            if (!$analytics30DaysAgo) continue;
            
            $positionBefore = $analytics30DaysAgo->avg_position ?? $positionNow;
            $positionDrop = $positionNow - $positionBefore;

            if ($positionDrop >= 5) {
                // Check if already in queue
                $alreadyQueued = \App\Models\AiReoptimizationQueue::where('content_id', $content->id)
                    ->where('status', 'pending')
                    ->exists();

                if (!$alreadyQueued) {
                    \App\Models\AiReoptimizationQueue::create([
                        'tenant_id'               => $this->tenantId,
                        'content_id'              => $content->id,
                        'trigger_reason'          => "Ranking dropped from #{$positionBefore} to #{$positionNow} (drop of {$positionDrop} positions)",
                        'position_before'         => (int) $positionBefore,
                        'position_after'          => $positionNow,
                        'optimization_directives' => [
                            'actions' => ['add_faq', 'update_statistics', 'strengthen_eeat', 'expand_entities'],
                            'target_position' => (int) ($positionBefore - 2),
                        ],
                        'status'   => 'pending',
                        'priority' => (int) ($positionDrop * 10), // Higher drop = higher priority
                        'scheduled_at' => now(),
                    ]);

                    \Log::info("Auto re-optimization queued for content ID {$content->id}: dropped {$positionDrop} positions.");
                }
            }
        }
    }
}

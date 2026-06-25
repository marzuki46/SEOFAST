<?php

namespace App\Services\Seo;

use App\Models\Content;
use App\Models\GscUrlInspection;
use App\Models\SeoFeedbackLoop;
use App\Models\AiReoptimizationQueue;

class RankingFeedbackService
{
    /**
     * Proses hasil URL inspection dan trigger aksi yang tepat
     */
    public function processInspectionResult(Content $content, GscUrlInspection $inspection): void
    {
        $feedback = SeoFeedbackLoop::firstOrCreate(['content_id' => $content->id]);

        $feedback->update([
            'gsc_coverage_state'    => $inspection->coverage_state,
            'gsc_verdict'           => $inspection->verdict,
            'gsc_indexing_state'    => $inspection->indexing_state,
            'gsc_robots_txt_state'  => $inspection->robots_txt_state,
            'gsc_page_fetch_state'  => $inspection->page_fetch_state,
            'gsc_last_crawl_time'   => $inspection->last_crawl_time,
            'gsc_last_sync_at'      => now(),
            'last_sync_at'          => now(),
        ]);

        // Deteksi masalah dan trigger aksi
        $this->detectAndActOnIssues($content, $inspection, $feedback);
    }

    private function detectAndActOnIssues(
        Content $content,
        GscUrlInspection $inspection,
        SeoFeedbackLoop $feedback
    ): void {
        match ($inspection->coverage_state) {

            // Ideal — tidak perlu aksi
            'Submitted and indexed' => null,

            // Terindeks tapi bukan URL yang kita inginkan sebagai canonical
            'Duplicate without user-selected canonical' => 
                $this->flagCanonicalMismatch($content, $inspection),

            // Sudah dicrawl tapi tidak diindeks — cek konten
            'Crawled - currently not indexed' => 
                $this->triggerContentAudit($content, $feedback),

            // Belum pernah dicrawl — prioritaskan di sitemap
            'Discovered - currently not indexed' =>
                $this->escalateCrawlPriority($content, $feedback),

            // Diblokir — cek robots.txt / noindex tag
            'Excluded by \'noindex\' tag',
            'Blocked by robots.txt' =>
                $this->alertBlockedContent($content, $inspection),

            default => null,
        };
    }

    private function triggerContentAudit(Content $content, SeoFeedbackLoop $feedback): void
    {
        // Jika sudah crawled-not-indexed > 14 hari → trigger re-optimasi
        $daysSincePublish = $content->published_at?->diffInDays(now()) ?? 0;

        if ($daysSincePublish > 14 && $feedback->reoptimization_count < 3) {
            AiReoptimizationQueue::firstOrCreate(
                ['content_id' => $content->id, 'status' => 'pending'],
                [
                    'trigger_reason'          => 'not_indexed',
                    'status'                  => 'pending',
                    'priority'                => 8,
                    'optimization_directives' => [
                        'improve_eeat'        => true,
                        'add_author_bio'      => true,
                        'expand_depth'        => true,
                        'add_internal_links'  => true,
                    ],
                    'scheduled_at'            => now()->addHours(2),
                ]
            );

            $content->update(['status' => 'needs_reoptimize']);
        }
    }

    private function escalateCrawlPriority(Content $content, SeoFeedbackLoop $feedback): void
    {
        // Naikkan crawl priority score dan submit ulang ke Indexing API
        $feedback->increment('crawl_priority_score', 20.0);
        
        // Dispatch job submit ke Indexing API
        \App\Jobs\Gsc\SubmitToIndexingApiJob::dispatch($content)->delay(now()->addMinutes(30));
    }

    private function flagCanonicalMismatch(Content $content, GscUrlInspection $inspection): void
    {
        if ($inspection->canonical_selected_by_google !== $inspection->canonical_declared_in_page) {
            \Log::warning("Canonical mismatch for content {$content->id}", [
                'declared' => $inspection->canonical_declared_in_page,
                'selected' => $inspection->canonical_selected_by_google,
            ]);
        }
    }

    private function alertBlockedContent(Content $content, GscUrlInspection $inspection): void
    {
        // Kirim notifikasi ke admin tenant — konten terpublish tapi diblokir
        \App\Notifications\BlockedContentAlert::dispatch($content, $inspection);
    }
}

<?php

namespace App\Jobs\Ai;

use App\Models\AiGenerationJob;
use App\Models\Content;
use App\Models\ContentCampaignSchedule;
use App\Models\ContentTopic;
use App\Services\ContentCampaignScheduleService;
use App\Services\ContentCampaignService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GenerateCampaignContentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;

    public int $timeout = 120;

    public function __construct(public int $scheduleId)
    {
        $this->onQueue('ai');
    }

    public function handle(ContentCampaignService $topics, ContentCampaignScheduleService $scheduler): void
    {
        $lock = Cache::lock('campaign-schedule:'.$this->scheduleId, 180);
        if (! $lock->get()) {
            return;
        }
        try {
            $schedule = ContentCampaignSchedule::with('campaign')->find($this->scheduleId);
            if (! $schedule || $schedule->status !== 'pending' || $schedule->scheduled_at->isFuture()) {
                return;
            }
            $schedule->update(['status' => 'processing', 'locked_at' => now(), 'attempt_count' => $schedule->attempt_count + 1]);
            $campaign = $schedule->campaign;
            if (! $campaign || $campaign->status !== 'active') {
                $schedule->update(['status' => 'cancelled']);

                return;
            }

            $topic = DB::transaction(function () use ($campaign) {
                $topic = ContentTopic::withoutGlobalScopes()->where('campaign_id', $campaign->id)
                    ->where('status', 'idea')
                    ->orderByRaw('CASE WHEN campaign_target_id IS NOT NULL THEN 0 ELSE 1 END')
                    ->orderBy('id')->lockForUpdate()->first();
                if ($topic) {
                    $topic->update(['status' => 'processing']);
                }

                return $topic;
            });

            // Cek stok topik idea: jika kosong atau tinggal 1, otomatis generate ulang seluruh funnel masing-masing 5
            $remainingIdeaCount = ContentTopic::withoutGlobalScopes()->where('campaign_id', $campaign->id)->where('status', 'idea')->count();
            if ($remainingIdeaCount <= 1) {
                try {
                    $topics->replenishTopicsAllFunnels($campaign, 5);
                } catch (\Throwable $e) {
                    Log::warning('Auto replenish topics in job failed: '.$e->getMessage());
                }
            }

            if (! $topic) {
                // Ambil kembali setelah replenish selesai
                $topic = DB::transaction(function () use ($campaign) {
                    $t = ContentTopic::withoutGlobalScopes()->where('campaign_id', $campaign->id)
                        ->where('status', 'idea')
                        ->orderBy('id')->lockForUpdate()->first();
                    if ($t) {
                        $t->update(['status' => 'processing']);
                    }

                    return $t;
                });
            }

            if (! $topic) {
                $schedule->update(['status' => 'failed', 'error_message' => 'Topic bank kosong dan riset AI belum menghasilkan topik.']);
                $scheduler->ensureNext($campaign);

                return;
            }

            $slug = Str::slug($topic->primary_keyword) ?: 'artikel';
            $base = $slug;
            $counter = 1;
            while (Content::withoutGlobalScopes()->whereSlug($slug)->exists()) {
                $slug = $base.'-'.$counter++;
            }
            $content = Content::create([
                'tenant_id' => $campaign->tenant_id,
                'product_id' => $campaign->product_id,
                'campaign_id' => $campaign->id,
                'campaign_target_id' => $topic->campaign_target_id,
                'content_topic_id' => $topic->id,
                'silo_blueprint_id' => $campaign->silo_blueprint_id,
                'target_keyword' => $topic->primary_keyword,
                'slug' => $slug,
                'meta_title' => $topic->title,
                'meta_description' => Str::limit($topic->angle ?: $topic->title, 155),
                'hierarchy_level' => $topic->funnel_stage === 'tofu' ? 'pillar' : 'cluster',
                'status' => 'ai_processing',
            ]);
            $job = AiGenerationJob::create([
                'tenant_id' => $campaign->tenant_id,
                'content_id' => $content->id,
                'job_type' => 'campaign_generation',
                'status' => 'pending',
                'llm_model_used' => null,
            ]);
            $topic->update(['status' => 'used', 'content_id' => $content->id]);
            $schedule->update(['status' => 'completed', 'content_id' => $content->id, 'content_topic_id' => $topic->id]);
            $campaign->update(['last_run_at' => now(), 'next_run_at' => $scheduler->nextRunAt($campaign)]);
            $scheduler->ensureNext($campaign->fresh());
            ProcessAiGenerationJob::dispatch($content->id, $job->id, $campaign->publish_status);
        } finally {
            $lock->release();
        }
    }
}

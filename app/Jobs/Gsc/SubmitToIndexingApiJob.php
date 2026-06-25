<?php

namespace App\Jobs\Gsc;

use App\Models\Content;
use App\Models\SeoFeedbackLoop;
use App\Services\Gsc\GoogleSearchConsoleService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SubmitToIndexingApiJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries   = 3;
    public int $timeout = 300;

    public function __construct(public readonly Content $content) {}

    public function handle(): void
    {
        $tenantId = $this->content->tenant_id;
        
        try {
            $gscService = new GoogleSearchConsoleService($tenantId);
            $domain = $this->content->tenant->domain;
            $url = "https://{$domain}/{$this->content->slug}";

            $result = $gscService->submitToIndexingApi([$url]);

            $success = $result[$url]['success'] ?? false;

            if ($success) {
                $feedback = SeoFeedbackLoop::firstOrCreate(['content_id' => $this->content->id]);
                $feedback->update([
                    'last_submitted_to_indexing_api_at' => now(),
                ]);
                $feedback->increment('indexing_api_submission_count');
            } else {
                \Log::warning("Google Indexing API submission returned failure for {$url}", $result[$url]);
            }
        } catch (\Exception $e) {
            \Log::error("Google Indexing API submission failed for content {$this->content->id}: " . $e->getMessage());
        }
    }
}

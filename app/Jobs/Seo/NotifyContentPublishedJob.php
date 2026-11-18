<?php

namespace App\Jobs\Seo;

use App\Models\Content;
use App\Services\IndexNowService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NotifyContentPublishedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;

    public int $timeout = 240;

    public function __construct(public int $contentId)
    {
        // The production worker already consumes the ai queue for generated
        // content, so publication notifications are guaranteed to be picked up.
        $this->onQueue('ai');
    }

    public function handle(IndexNowService $indexNow): void
    {
        $content = Content::withoutGlobalScopes()->find($this->contentId);
        if (! $content || $content->status !== 'published' || ! $content->slug) {
            return;
        }

        try {
            $indexNow->submitPost($content);
        } catch (\Throwable $e) {
            Log::warning('Published content notification failed', [
                'content_id' => $this->contentId,
                'error' => $e->getMessage(),
            ]);
        }
    }
}

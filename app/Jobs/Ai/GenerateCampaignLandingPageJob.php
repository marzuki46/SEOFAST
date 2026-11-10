<?php

namespace App\Jobs\Ai;

use App\Models\ContentCampaignTarget;
use App\Services\ContentCampaignLandingPageService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class GenerateCampaignLandingPageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;

    public int $timeout = 120;

    public function __construct(public int $targetId)
    {
        $this->onQueue('ai');
    }

    public function handle(ContentCampaignLandingPageService $service): void
    {
        $lock = Cache::lock('campaign-target:'.$this->targetId, 180);
        if (! $lock->get()) {
            return;
        }

        try {
            $target = ContentCampaignTarget::with('campaign')->find($this->targetId);
            if (! $target || $target->status === 'ready') {
                return;
            }

            $target->update(['status' => 'generating', 'error_message' => null]);
            try {
                $service->create($target);
            } catch (\Throwable $exception) {
                $target->update(['status' => 'failed', 'error_message' => 'Landing page generation failed.']);
                throw $exception;
            }
        } finally {
            $lock->release();
        }
    }
}

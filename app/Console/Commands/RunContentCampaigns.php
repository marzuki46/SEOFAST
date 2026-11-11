<?php

namespace App\Console\Commands;

use App\Jobs\Ai\GenerateCampaignContentJob;
use App\Models\ContentCampaign;
use App\Models\ContentCampaignSchedule;
use App\Services\ContentCampaignScheduleService;
use Illuminate\Console\Command;

class RunContentCampaigns extends Command
{
    protected $signature = 'seofast:run-content-campaigns {--limit=10}';

    protected $description = 'Dispatch due product content campaign schedules safely.';

    public function handle(ContentCampaignScheduleService $scheduler): int
    {
        // Pastikan setiap campaign aktif selalu memiliki jadwal giliran berikutnya
        ContentCampaign::where('status', 'active')->get()->each(function (ContentCampaign $campaign) use ($scheduler) {
            $scheduler->ensureNext($campaign);
        });

        $limit = max(1, min(50, (int) $this->option('limit')));
        $schedules = ContentCampaignSchedule::with('campaign')
            ->where('status', 'pending')->where('scheduled_at', '<=', now())
            ->whereHas('campaign', fn ($query) => $query->where('status', 'active'))
            ->orderBy('scheduled_at')->limit($limit)->get();
        foreach ($schedules as $schedule) {
            GenerateCampaignContentJob::dispatch($schedule->id);
        }
        $this->info("Dispatched {$schedules->count()} campaign schedule(s).");

        return self::SUCCESS;
    }
}

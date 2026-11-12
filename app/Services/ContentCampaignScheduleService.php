<?php

namespace App\Services;

use App\Models\ContentCampaign;
use App\Models\ContentCampaignSchedule;
use App\Models\Content;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Log;

class ContentCampaignScheduleService
{
    public function ensureNext(ContentCampaign $campaign): ?ContentCampaignSchedule
    {
        if ($campaign->status !== 'active') {
            return null;
        }

        $this->recoverStaleSchedules($campaign);

        $now = now();
        $existing = $campaign->schedules()
            ->where(function ($query) use ($now) {
                $query->where(fn ($q) => $q->where('status', 'pending')->where('scheduled_at', '>=', $now))
                    ->orWhere(fn ($q) => $q->where('status', 'processing')->where('locked_at', '>=', $now->copy()->subMinutes(15)));
            })
            ->orderBy('scheduled_at')
            ->first();
        if ($existing) {
            $campaign->forceFill(['next_run_at' => $existing->scheduled_at])->saveQuietly();

            return $existing;
        }
        $at = $this->nextRunAt($campaign);
        $schedule = $campaign->schedules()->create(['scheduled_at' => $at, 'status' => 'pending']);
        $campaign->forceFill(['next_run_at' => $at])->saveQuietly();

        return $schedule;
    }

    /**
     * Return abandoned processing schedules to the queue without creating
     * duplicate content. A worker crash must not permanently block a campaign.
     */
    private function recoverStaleSchedules(ContentCampaign $campaign): void
    {
        $cutoff = now()->subMinutes(15);
        $stale = $campaign->schedules()
            ->where('status', 'processing')
            ->where(function ($query) use ($cutoff) {
                $query->whereNull('locked_at')->orWhere('locked_at', '<', $cutoff);
            })
            ->get();

        foreach ($stale as $schedule) {
            if ($schedule->content_id && Content::withoutGlobalScopes()->whereKey($schedule->content_id)->whereNotIn('status', ['ai_processing'])->exists()) {
                $schedule->update(['status' => 'completed', 'locked_at' => null]);
                continue;
            }

            if ($schedule->content_topic_id) {
                $campaign->topics()->whereKey($schedule->content_topic_id)->where('status', 'processing')->update([
                    'status' => 'idea',
                    'content_id' => null,
                ]);
            }

            $schedule->update([
                'status' => 'pending',
                'locked_at' => null,
                'error_message' => 'Recovered after an abandoned queue worker.',
            ]);

            Log::warning('Recovered stale content campaign schedule', [
                'campaign_id' => $campaign->id,
                'schedule_id' => $schedule->id,
            ]);
        }
    }

    public function nextRunAt(ContentCampaign $campaign): CarbonImmutable
    {
        $tz = 'Asia/Jakarta';
        $now = CarbonImmutable::now($tz);
        if ($campaign->schedule_type === 'fixed') {
            $hours = collect(explode(',', (string) $campaign->fixed_hours))
                ->map(fn ($value) => trim($value))->filter(fn ($value) => preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $value))
                ->sort()->values();
            foreach ($hours as $hour) {
                $candidate = CarbonImmutable::parse($now->format('Y-m-d').' '.$hour, $tz);
                if ($candidate->isFuture()) {
                    return $candidate;
                }
            }
            $first = $hours->first() ?: '09:00';

            return CarbonImmutable::parse($now->addDay()->format('Y-m-d').' '.$first, $tz);
        }
        $start = $now->setTime(7, 0);
        $end = $now->setTime(22, 0);
        $slots = max(1, min(24, (int) $campaign->posts_per_day));
        $step = max(15, intdiv($start->diffInMinutes($end), $slots));
        for ($i = 0; $i < $slots; $i++) {
            $candidate = $start->addMinutes($i * $step + random_int(0, max(1, min(30, $step - 1))));
            if ($candidate->isFuture()) {
                return $candidate;
            }
        }

        return $now->addMinutes(10);
    }
}

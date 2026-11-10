<?php

namespace App\Services;

use App\Models\ContentCampaign;
use App\Models\ContentCampaignSchedule;
use Carbon\CarbonImmutable;

class ContentCampaignScheduleService
{
    public function ensureNext(ContentCampaign $campaign): ?ContentCampaignSchedule
    {
        if ($campaign->status !== 'active') {
            return null;
        }
        $existing = $campaign->schedules()->whereIn('status', ['pending', 'processing'])->where('scheduled_at', '>=', now())->first();
        if ($existing) {
            return $existing;
        }
        $at = $this->nextRunAt($campaign);

        return $campaign->schedules()->create(['scheduled_at' => $at, 'status' => 'pending']);
    }

    public function nextRunAt(ContentCampaign $campaign): CarbonImmutable
    {
        $tz = $campaign->timezone ?: config('app.timezone', 'UTC');
        $now = CarbonImmutable::now($tz);
        if ($campaign->schedule_type === 'fixed') {
            $hours = collect(explode(',', (string) $campaign->fixed_hours))
                ->map(fn ($value) => trim($value))->filter(fn ($value) => preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $value))
                ->sort()->values();
            foreach ($hours as $hour) {
                $candidate = CarbonImmutable::parse($now->format('Y-m-d').' '.$hour, $tz);
                if ($candidate->isFuture()) {
                    return $candidate->setTimezone('UTC');
                }
            }
            $first = $hours->first() ?: '09:00';

            return CarbonImmutable::parse($now->addDay()->format('Y-m-d').' '.$first, $tz)->setTimezone('UTC');
        }
        $start = $now->setTime(7, 0);
        $end = $now->setTime(22, 0);
        $slots = max(1, min(24, (int) $campaign->posts_per_day));
        $step = max(15, intdiv($start->diffInMinutes($end), $slots));
        for ($i = 0; $i < $slots; $i++) {
            $candidate = $start->addMinutes($i * $step + random_int(0, max(1, min(30, $step - 1))));
            if ($candidate->isFuture()) {
                return $candidate->setTimezone('UTC');
            }
        }

        return $now->addMinutes(10)->setTimezone('UTC');
    }
}

<?php

namespace App\Notifications;

use App\Models\Content;
use App\Models\GscUrlInspection;
use Illuminate\Support\Facades\Log;

class BlockedContentAlert
{
    public static function dispatch(Content $content, GscUrlInspection $inspection): void
    {
        Log::warning("GSC BLOCKED CONTENT ALERT: Content {$content->id} ('{$content->slug}') is blocked. Verdict: {$inspection->verdict}, Coverage State: {$inspection->coverage_state}. Robots.txt: {$inspection->robots_txt_state}");
    }
}

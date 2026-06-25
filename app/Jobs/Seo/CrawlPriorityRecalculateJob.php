<?php

namespace App\Jobs\Seo;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CrawlPriorityRecalculateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        // Placeholder for maintenance task updating crawl priority scores daily
        \Log::info("CrawlPriorityRecalculateJob placeholder executed.");
    }
}

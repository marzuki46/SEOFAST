<?php

namespace App\Jobs\Seo;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessSerpSnapshotsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        // Placeholder for daily SERP snapshots tracking (DataForSEO / Semrush Integration)
        \Log::info("ProcessSerpSnapshotsJob placeholder executed.");
    }
}

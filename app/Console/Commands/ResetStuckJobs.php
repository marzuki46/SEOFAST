<?php

namespace App\Console\Commands;

use App\Models\AiGenerationJob;
use App\Models\Content;
use Illuminate\Console\Command;

class ResetStuckJobs extends Command
{
    protected $signature = 'seofast:reset-stuck-jobs';

    protected $description = 'Reset konten yang terjebak di status ai_processing dan batalkan job AI yang menggantung.';

    public function handle(): int
    {
        $updatedContents = Content::withoutGlobalScopes()
            ->where('status', 'ai_processing')
            ->update(['status' => 'blueprint']);

        $updatedJobs = AiGenerationJob::withoutGlobalScopes()
            ->whereIn('status', ['pending', 'processing', 'phase_1', 'phase_2', 'phase_3', 'phase_4', 'phase_5', 'phase_6', 'phase_7'])
            ->update(['status' => 'failed', 'error_log' => ['reason' => 'Manually reset by admin']]);

        $this->info("Reset selesai. Konten kembali ke Blueprint: {$updatedContents}. Job AI dibatalkan: {$updatedJobs}.");

        return self::SUCCESS;
    }
}

<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Content;
use App\Models\AiGenerationJob;
use App\Jobs\Ai\ProcessAiGenerationJob;

$contents = Content::withoutGlobalScopes()->where('status', 'ai_processing')->get();
$count = 0;

foreach ($contents as $content) {
    $existingJob = AiGenerationJob::withoutGlobalScopes()
        ->where('content_id', $content->id)
        ->whereIn('status', ['pending', 'processing', 'phase_1', 'phase_2', 'phase_3', 'phase_4'])
        ->first();

    if (!$existingJob) {
        $job = AiGenerationJob::create([
            'tenant_id'  => $content->tenant_id ?? (\App\Models\Tenant::first()?->id ?? 1),
            'content_id' => $content->id,
            'job_type'   => 'initial_generation',
            'status'     => 'pending',
            'retry_count'=> 0
        ]);

        ProcessAiGenerationJob::dispatch($content->id, $job->id, 'draft');
        $count++;
    } else {
        // If it exists, dispatch it anyway just in case it's not in the Laravel queue
        ProcessAiGenerationJob::dispatch($content->id, $existingJob->id, 'draft');
        $count++;
    }
}

echo "Dispatched $count jobs.\n";

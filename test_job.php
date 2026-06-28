<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $job = \App\Models\AiGenerationJob::create([
        'tenant_id' => 1,
        'content_id' => \App\Models\Content::withoutGlobalScopes()->where('status', 'ai_processing')->first()->id ?? 7,
        'job_type' => 'initial_generation',
        'status' => 'pending',
        'retry_count' => 0
    ]);
    echo "Success! Job ID: " . $job->id . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

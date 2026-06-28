<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$jobs = \App\Models\AiGenerationJob::orderBy('id', 'desc')->take(3)->get();
foreach($jobs as $job) {
    echo "ID: {$job->id} | Status: {$job->status} | Content ID: {$job->content_id} | Retries: {$job->retry_count}\n";
    if ($job->status === 'failed' || $job->status === 'failed_cqi') {
        echo "Error: " . json_encode($job->error_log) . "\n";
    }
}
$contents = \App\Models\Content::withoutGlobalScopes()->whereIn('id', $jobs->pluck('content_id'))->get();
foreach($contents as $c) {
    echo "Content ID: {$c->id} | Status: {$c->status} | HTML Length: " . strlen($c->rendered_html_path ?? '') . "\n";
}

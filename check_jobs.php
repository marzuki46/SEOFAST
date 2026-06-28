<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$jobs = \App\Models\AiGenerationJob::orderBy('id', 'desc')->take(5)->get();
foreach($jobs as $job) {
    echo "ID: {$job->id} | Status: {$job->status} | Created: {$job->created_at}\n";
}

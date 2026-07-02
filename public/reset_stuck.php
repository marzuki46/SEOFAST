<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// Reset stuck jobs
$updatedContents = \App\Models\Content::withoutGlobalScopes()
    ->where('status', 'ai_processing')
    ->update(['status' => 'blueprint']);

$updatedJobs = \App\Models\AiGenerationJob::withoutGlobalScopes()
    ->whereIn('status', ['pending', 'processing', 'phase_1', 'phase_2', 'phase_3', 'phase_4', 'phase_5', 'phase_6', 'phase_7'])
    ->update(['status' => 'failed', 'error_log' => ['reason' => 'Manually reset by admin']]);

echo "<h1>Reset Berhasil</h1>";
echo "<p>Jumlah Konten yang dikembalikan ke Blueprint: " . $updatedContents . "</p>";
echo "<p>Jumlah Job AI yang dibatalkan: " . $updatedJobs . "</p>";
echo "<br><a href='/admin/content/prapost'>Kembali ke Halaman Pra-Post</a>";

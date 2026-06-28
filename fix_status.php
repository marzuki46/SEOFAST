<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    DB::statement("ALTER TABLE contents MODIFY COLUMN status ENUM('blueprint', 'ai_processing', 'failed_cqi', 'draft', 'published', 'needs_reoptimize') DEFAULT 'blueprint'");
    DB::statement("ALTER TABLE contents MODIFY COLUMN rendered_html_path LONGTEXT");
    echo "Schema fixed successfully!\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

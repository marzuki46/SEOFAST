<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

\App\Models\Content::withoutGlobalScopes()->get()->each(function($c) {
    $raw = $c->getRawOriginal('slug');
    if (is_string($raw)) {
        $decoded = json_decode($raw, true);
        if (is_string($decoded)) {
            $realArray = json_decode($decoded, true);
            if (is_array($realArray)) {
                $c->slug = $realArray;
                $c->save();
            }
        }
    }
});
echo "Fixed DB Slugs!\n";

<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Content;

$contents = Content::withoutGlobalScopes()->get();
$count = 0;
foreach ($contents as $content) {
    $dirty = false;

    // Helper to extract string from json
    $clean = function($val) {
        if (is_string($val) && (str_starts_with(trim($val), '{') || str_starts_with(trim($val), '"{'))) {
            $decoded = json_decode(trim($val, '"'), true);
            if (is_array($decoded)) {
                return $decoded['id'] ?? current($decoded);
            }
        }
        return $val;
    };

    $slug = $clean($content->slug);
    if ($slug !== $content->slug) { $content->slug = $slug; $dirty = true; }

    $title = $clean($content->meta_title);
    if ($title !== $content->meta_title) { $content->meta_title = $title; $dirty = true; }

    $desc = $clean($content->meta_description);
    if ($desc !== $content->meta_description) { $content->meta_description = $desc; $dirty = true; }

    $body = $clean($content->body_raw);
    if ($body !== $content->body_raw) { $content->body_raw = $body; $dirty = true; }

    if ($dirty) {
        $content->save();
        $count++;
    }
}

echo "Cleaned $count records.\n";

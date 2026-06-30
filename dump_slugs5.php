<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$c = \App\Models\Content::withoutGlobalScopes()->whereRaw('slug LIKE ?', ['%cara-optimasi-meta-description-untuk-meningkatkan-ctr-di-google%'])->first();
if ($c) {
    echo "Exists: " . $c->id . "\n";
    echo "Slug in DB: " . $c->getRawOriginal('slug') . "\n";
} else {
    echo "Not Found in slug\n";
    // Check target_keyword
    $c = \App\Models\Content::withoutGlobalScopes()->where('target_keyword', 'LIKE', '%cara optimasi meta description%')->first();
    if ($c) {
        echo "Found by keyword: " . $c->id . "\n";
        echo "Slug in DB: " . $c->getRawOriginal('slug') . "\n";
    }
}

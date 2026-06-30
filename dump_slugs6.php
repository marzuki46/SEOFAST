<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$contents = \App\Models\Content::withoutGlobalScopes()->where('status', 'draft')->get();
echo "Total Drafts: " . $contents->count() . "\n";
foreach ($contents as $c) {
    echo "ID: {$c->id} | Keyword: {$c->target_keyword} | Slug: " . $c->getRawOriginal('slug') . "\n";
}

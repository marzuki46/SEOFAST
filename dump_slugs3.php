<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$contents = \App\Models\Content::withoutGlobalScopes()->where('target_keyword', 'LIKE', '%Cara optimasi%')->get();
foreach ($contents as $c) {
    echo "ID: {$c->id}\n";
    echo "Title: {$c->title}\n";
    echo "Keyword: {$c->target_keyword}\n";
    echo "Slug: " . $c->getRawOriginal('slug') . "\n";
    echo "Status: " . $c->status . "\n";
    echo "-------------------\n";
}

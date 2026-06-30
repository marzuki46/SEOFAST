<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$contents = \App\Models\Content::withoutGlobalScopes()->where('slug', 'LIKE', '%cara-optimasi%')->get();
foreach ($contents as $c) {
    echo "ID: {$c->id}\n";
    echo "Title: {$c->title}\n";
    echo "Slug: " . $c->getRawOriginal('slug') . "\n";
    echo "Status: " . $c->status . "\n";
    echo "-------------------\n";
}

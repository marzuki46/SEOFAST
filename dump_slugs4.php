<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$contents = \App\Models\Content::withoutGlobalScopes()->latest('id')->take(25)->get();
foreach ($contents as $c) {
    echo "ID: {$c->id} | Keyword: {$c->target_keyword} | Slug: " . $c->getRawOriginal('slug') . "\n";
}

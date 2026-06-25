<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$post = \App\Models\Content::where('slug', 'how-to-automate-blog-content-generation-with-claude-and-chatgpt')->first();
$category = $post ? $post->siloBlueprint : null;
try {
    echo view('blog.show', compact('post', 'category'))->render();
    echo "SUCCESS\n";
} catch (\Throwable $e) {
    echo 'ERROR: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine();
}

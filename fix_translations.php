<?php
$views = [
    'resources/views/blog/index.blade.php',
    'resources/views/blog/show.blade.php',
    'resources/views/blog/category.blade.php',
    'resources/views/admin/content/prapost.blade.php',
    'resources/views/admin/content/index.blade.php',
    'resources/views/admin/content/drafts.blade.php',
    'resources/views/admin/content/edit.blade.php',
    'app/Services/SeoHelper.php',
];

foreach ($views as $view) {
    $path = __DIR__ . '/' . $view;
    if (!file_exists($path)) continue;
    $c = file_get_contents($path);
    
    // Replace $post->slug ?: $post->getTranslation(...) -> $post->slug
    $c = preg_replace('/\$([a-zA-Z0-9_]+)\->slug\s*\?\:\s*\$\1\->getTranslation\(\'slug\',\s*\'[a-z]+\',\s*false\)/', '$$1->slug', $c);
    
    // Replace $post->getTranslation('slug', app()->getLocale(), false) ?: ... -> $post->slug
    $c = preg_replace('/\$([a-zA-Z0-9_]+)\->getTranslation\(\'slug\',\s*app\(\)\->getLocale\(\),\s*false\)\s*\?\:\s*\$\1\->getTranslation\(\'slug\',\s*\'[a-z]+\',\s*false\)\s*\?\:\s*\$\1\->slug/', '$$1->slug', $c);

    // Replace edit.blade.php leftovers
    $c = preg_replace('/\$content\->getTranslation\(\'body_raw\',\s*\'[a-z]+\',\s*false\)\s*\?\:\s*/', '', $c);
    $c = preg_replace('/\$content\->getTranslation\(\'rendered_html_path\',\s*\'[a-z]+\',\s*false\)\s*\?\:\s*/', '', $c);

    // SeoHelper leftover
    $c = preg_replace('/\$post\->getTranslation\(\'slug\',\s*\$targetLocale,\s*false\)/', '$post->slug', $c);

    file_put_contents($path, $c);
}
echo "Done\n";

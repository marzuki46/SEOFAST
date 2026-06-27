<?php
$views = [
    'resources/views/admin/content/prapost.blade.php',
    'resources/views/admin/content/index.blade.php',
    'resources/views/admin/content/drafts.blade.php',
];

foreach ($views as $view) {
    $path = __DIR__ . '/' . $view;
    if (!file_exists($path)) continue;
    $c = file_get_contents($path);
    
    // Remove the while loops
    $c = preg_replace('/while\s*\(is_array\(\$rawSlugDisp\)\)\s*\{[^\}]+\}/s', '', $c);
    $c = preg_replace('/while\s*\(is_array\(\$rawSlug\)\)\s*\{[^\}]+\}/s', '', $c);
    
    // Replace $slugStr = is_string(...) ? ...
    $c = preg_replace('/\$slugStr\s*=\s*is_string\(\$rawSlugDisp\)\s*\?\s*\$rawSlugDisp\s*:\s*\'[^\']+\'\;/', '$slugStr = $rawSlugDisp;', $c);
    $c = preg_replace('/\$slugStr\s*=\s*is_string\(\$rawSlug\)\s*\?\s*\$rawSlug\s*:\s*\'[^\']+\'\;/', '$slugStr = $rawSlug;', $c);

    file_put_contents($path, $c);
}
echo "Done\n";

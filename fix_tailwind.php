<?php

$dir = __DIR__ . '/resources/views/admin';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        $original = $content;

        // Replace invalid tailwind classes
        $content = str_replace('h-4.5 w-4.5', 'h-4 w-4', $content);
        $content = str_replace('w-4.5 h-4.5', 'w-4 h-4', $content);
        $content = str_replace('h-4.5', 'h-4', $content);
        $content = str_replace('w-4.5', 'w-4', $content);


        if ($content !== $original) {
            file_put_contents($file->getPathname(), $content);
            echo "Updated: " . $file->getPathname() . "\n";
        }
    }
}
echo "Done.\n";

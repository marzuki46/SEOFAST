<?php

$dir = __DIR__ . '/resources/views/admin';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        $original = $content;

        // Replace large paddings and texts only inside <button>, <a>, <input>, <select>, <textarea> tags
        $content = preg_replace_callback('/<(button|a|input|select|textarea)\b([^>]+)>/i', function($matches) {
            $tag = $matches[1];
            $attrs = $matches[2];

            // Replace padding and text sizes in the class attribute
            if (preg_match('/class="([^"]+)"/i', $attrs, $classMatch)) {
                $classes = $classMatch[1];
                $classes = preg_replace('/\bpx-[68]\s+py-[34]\b/', 'px-4 py-2.5', $classes);
                $classes = preg_replace('/\bpx-4\s+py-3\b/', 'px-4 py-2', $classes);
                $classes = preg_replace('/\bpx-6\s+py-2\b/', 'px-4 py-2', $classes);
                $classes = preg_replace('/\bpx-5\s+py-3\b/', 'px-4 py-2.5', $classes);
                
                $classes = preg_replace('/\btext-lg\b/', 'text-sm', $classes);
                $classes = preg_replace('/\btext-base\b/', 'text-sm', $classes);

                // Reconstruct attributes
                $attrs = str_replace($classMatch[0], 'class="' . $classes . '"', $attrs);
            }

            return "<$tag$attrs>";
        }, $content);


        if ($content !== $original) {
            file_put_contents($file->getPathname(), $content);
            echo "Updated: " . $file->getPathname() . "\n";
        }
    }
}
echo "Done.\n";

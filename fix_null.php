<?php
$content = file_get_contents('app/Http/Controllers/Admin/InternalLinkController.php');
// Remove null bytes
$content = str_replace("\x00", "", $content);
// Remove BOM
$content = ltrim($content, "\xEF\xBB\xBF");
file_put_contents('app/Http/Controllers/Admin/InternalLinkController.php', $content);
echo "Cleaned!";

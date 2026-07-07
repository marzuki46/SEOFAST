<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    public function processUpload(UploadedFile $file, string $directory = 'content-images', int $maxWidth = 1200): ?array
    {
        $tempPath = $this->compressToWebp($file->getPathname(), 80, $maxWidth);
        if (!$tempPath) {
            $path = $file->store($directory, 'public');
            $url = '/storage/' . $path;
            return [
                'path' => $path,
                'url'  => $url,
                'size' => $file->getSize(),
            ];
        }

        $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $filename));
        $name = $slug . '-' . time() . '.webp';
        $destPath = $directory . '/' . $name;

        Storage::disk('public')->put($destPath, file_get_contents($tempPath));
        @unlink($tempPath);

        return [
            'path' => $destPath,
            'url'  => '/storage/' . $destPath,
            'size' => Storage::disk('public')->size($destPath),
        ];
    }

    public function downloadAndProcess(string $url, string $directory = 'content-images', int $maxWidth = 1200): ?array
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_USERAGENT      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        ]);
        $data = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode < 200 || $httpCode >= 300 || !$data) {
            return null;
        }

        $tmp = tempnam(sys_get_temp_dir(), 'dl_');
        file_put_contents($tmp, $data);

        $tempPath = $this->compressToWebp($tmp, 80, $maxWidth);
        @unlink($tmp);

        if (!$tempPath) {
            $ext = 'jpg';
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_buffer($finfo, $data);
            finfo_close($finfo);
            $ext = match ($mime) {
                'image/jpeg' => 'jpg',
                'image/png'  => 'png',
                'image/webp' => 'webp',
                'image/gif'  => 'gif',
                default      => 'jpg',
            };
            $name = 'dl-' . md5($url) . '-' . time() . '.' . $ext;
            $destPath = $directory . '/' . $name;
            Storage::disk('public')->put($destPath, $data);
            return [
                'path' => $destPath,
                'url'  => '/storage/' . $destPath,
                'size' => strlen($data),
            ];
        }

        $name = 'dl-' . md5($url) . '-' . time() . '.webp';
        $destPath = $directory . '/' . $name;
        Storage::disk('public')->put($destPath, file_get_contents($tempPath));
        @unlink($tempPath);

        return [
            'path' => $destPath,
            'url'  => '/storage/' . $destPath,
            'size' => Storage::disk('public')->size($destPath),
        ];
    }

    public function compressToWebp(string $sourcePath, int $quality = 80, int $maxWidth = 1200): ?string
    {
        $info = @getimagesize($sourcePath);
        if (!$info) return null;

        $width = $info[0];
        $height = $info[1];
        $mime = $info['mime'];

        $newWidth = $width;
        $newHeight = $height;
        if ($width > $maxWidth || $height > $maxWidth) {
            if ($width > $height) {
                $newWidth = $maxWidth;
                $newHeight = intval($height * ($maxWidth / $width));
            } else {
                $newHeight = $maxWidth;
                $newWidth = intval($width * ($maxWidth / $height));
            }
        }

        $image = match ($mime) {
            'image/jpeg' => @imagecreatefromjpeg($sourcePath),
            'image/png'  => @imagecreatefrompng($sourcePath),
            'image/gif'  => @imagecreatefromgif($sourcePath),
            'image/webp' => @imagecreatefromwebp($sourcePath),
            default      => null,
        };

        if (!$image) return null;

        $newImage = imagecreatetruecolor($newWidth, $newHeight);

        if ($mime === 'image/png' || $mime === 'image/webp') {
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
            $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
            imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);
        }

        imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        $tempPath = sys_get_temp_dir() . '/' . uniqid('img_', true) . '.webp';
        imagewebp($newImage, $tempPath, $quality);

        imagedestroy($image);
        imagedestroy($newImage);

        return $tempPath;
    }

    public function generateOgImage(string $title, string $slug): ?string
    {
        $w = 1200;
        $h = 630;

        $img = imagecreatetruecolor($w, $h);

        $c1 = imagecolorallocate($img, 99, 102, 241);
        $c2 = imagecolorallocate($img, 139, 92, 246);
        $this->drawGradient($img, $w, $h, $c1, $c2);

        $white = imagecolorallocate($img, 255, 255, 255);
        $gray = imagecolorallocate($img, 220, 220, 250);
        $fontSize = 48;

        $lines = $this->wordWrapText($title, 28);
        $lineHeight = 60;
        $y = ($h - (count($lines) * $lineHeight)) / 2;

        foreach ($lines as $line) {
            $this->drawCenteredText($img, $fontSize, 0, $y, $white, $line);
            $y += $lineHeight;
        }

        $this->drawCenteredText($img, 18, 0, $h - 60, $gray, 'SEOFAST — seofast.ai');

        $tempPath = sys_get_temp_dir() . '/' . uniqid('og_', true) . '.webp';
        imagewebp($img, $tempPath, 85);
        imagedestroy($img);

        $name = 'og-' . $slug . '-' . time() . '.webp';
        $destPath = 'og-images/' . $name;
        Storage::disk('public')->put($destPath, file_get_contents($tempPath));
        @unlink($tempPath);

        return '/storage/' . $destPath;
    }

    private function drawGradient($img, int $w, int $h, $c1, $c2): void
    {
        for ($y = 0; $y < $h; $y++) {
            $r = ($c1 >> 16 & 0xFF) + (($c2 >> 16 & 0xFF) - ($c1 >> 16 & 0xFF)) * $y / $h;
            $g = ($c1 >> 8 & 0xFF) + (($c2 >> 8 & 0xFF) - ($c1 >> 8 & 0xFF)) * $y / $h;
            $b = ($c1 & 0xFF) + (($c2 & 0xFF) - ($c1 & 0xFF)) * $y / $h;
            $color = imagecolorallocate($img, (int) $r, (int) $g, (int) $b);
            imageline($img, 0, $y, $w, $y, $color);
        }
    }

    private function drawCenteredText($img, int $size, int $x, int $y, $color, string $text): void
    {
        $fontFile = resource_path('fonts/Poppins-SemiBold.ttf');
        if (!file_exists($fontFile)) {
            $fontFile = resource_path('fonts/Inter-SemiBold.ttf');
        }
        if (!file_exists($fontFile)) {
            $fallbacks = glob(resource_path('fonts/*.ttf'));
            $fontFile = $fallbacks[0] ?? null;
        }

        if ($fontFile) {
            $bbox = imagettfbbox($size, 0, $fontFile, $text);
            $tw = $bbox[2] - $bbox[0];
            $tx = ($x === 0) ? imagesx($img) / 2 - $tw / 2 : $x;
            imagettftext($img, $size, 0, (int) $tx, $y, $color, $fontFile, $text);
        } else {
            $tw = strlen($text) * imagefontwidth(5);
            $tx = ($x === 0) ? imagesx($img) / 2 - $tw / 2 : $x;
            imagestring($img, 5, (int) $tx, $y, $text, $color);
        }
    }

    private function wordWrapText(string $text, int $maxChars): array
    {
        $words = explode(' ', $text);
        $lines = [];
        $current = '';

        foreach ($words as $word) {
            if (strlen($current . ' ' . $word) <= $maxChars) {
                $current .= ($current ? ' ' : '') . $word;
            } else {
                if ($current) $lines[] = $current;
                $current = $word;
            }
        }
        if ($current) $lines[] = $current;

        return $lines ?: [''];
    }
}

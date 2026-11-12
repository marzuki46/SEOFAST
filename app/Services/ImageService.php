<?php

namespace App\Services;

use App\Models\SystemSetting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    public function processUpload(UploadedFile $file, string $directory = 'content-images', int $maxWidth = 1200): ?array
    {
        $tempPath = $this->compressToWebp($file->getPathname(), 80, $maxWidth);
        if (! $tempPath) {
            $path = $file->store($directory, 'public');
            $url = '/storage/'.$path;

            return [
                'path' => $path,
                'url' => $url,
                'size' => $file->getSize(),
            ];
        }

        $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $filename));
        $name = $slug.'-'.time().'.webp';
        $destPath = $directory.'/'.$name;

        Storage::disk('public')->put($destPath, file_get_contents($tempPath));
        @unlink($tempPath);

        return [
            'path' => $destPath,
            'url' => '/storage/'.$destPath,
            'size' => Storage::disk('public')->size($destPath),
        ];
    }

    public function downloadAndProcess(string $url, string $directory = 'content-images', int $maxWidth = 1200): ?array
    {
        if (! $this->isSafeRemoteUrl($url)) {
            return null;
        }

        $ch = curl_init();
        $data = '';
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_PROTOCOLS => CURLPROTO_HTTP | CURLPROTO_HTTPS,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            CURLOPT_WRITEFUNCTION => function ($handle, string $chunk) use (&$data): int {
                if (strlen($data) + strlen($chunk) > 10 * 1024 * 1024) {
                    return 0;
                }
                $data .= $chunk;

                return strlen($chunk);
            },
        ]);
        $success = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($success === false || $httpCode < 200 || $httpCode >= 300 || $data === '') {
            return null;
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $downloadedMime = $finfo ? finfo_buffer($finfo, $data) : false;
        if ($finfo) {
            finfo_close($finfo);
        }
        if (! in_array($downloadedMime, ['image/jpeg', 'image/png', 'image/webp', 'image/gif'], true)) {
            return null;
        }

        $tmp = tempnam(sys_get_temp_dir(), 'dl_');
        file_put_contents($tmp, $data);

        $tempPath = $this->compressToWebp($tmp, 80, $maxWidth);
        @unlink($tmp);

        if (! $tempPath) {
            $ext = 'jpg';
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_buffer($finfo, $data);
            finfo_close($finfo);
            $ext = match ($mime) {
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/webp' => 'webp',
                'image/gif' => 'gif',
                default => 'jpg',
            };
            $name = 'dl-'.md5($url).'-'.time().'.'.$ext;
            $destPath = $directory.'/'.$name;
            Storage::disk('public')->put($destPath, $data);

            return [
                'path' => $destPath,
                'url' => '/storage/'.$destPath,
                'size' => strlen($data),
            ];
        }

        $name = 'dl-'.md5($url).'-'.time().'.webp';
        $destPath = $directory.'/'.$name;
        Storage::disk('public')->put($destPath, file_get_contents($tempPath));
        @unlink($tempPath);

        return [
            'path' => $destPath,
            'url' => '/storage/'.$destPath,
            'size' => Storage::disk('public')->size($destPath),
        ];
    }

    public function isSafeRemoteUrl(string $url): bool
    {
        $parts = parse_url($url);
        if (! is_array($parts) || ! in_array(strtolower($parts['scheme'] ?? ''), ['http', 'https'], true)) {
            return false;
        }

        $host = $parts['host'] ?? '';
        if ($host === '' || strtolower($host) === 'localhost') {
            return false;
        }

        $addresses = filter_var($host, FILTER_VALIDATE_IP)
            ? [$host]
            : array_values(array_unique(array_filter([
                gethostbyname($host),
                ...array_map(
                    static fn (array $record): ?string => $record['ipv6'] ?? $record['ip'] ?? null,
                    dns_get_record($host, DNS_A | DNS_AAAA) ?: []
                ),
            ])));

        if ($addresses === [] || (count($addresses) === 1 && $addresses[0] === $host && ! filter_var($host, FILTER_VALIDATE_IP))) {
            return false;
        }

        foreach ($addresses as $address) {
            if (! filter_var($address, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                return false;
            }
        }

        return true;
    }

    public function compressToWebp(string $sourcePath, int $quality = 80, int $maxWidth = 1200): ?string
    {
        $info = @getimagesize($sourcePath);
        if (! $info) {
            return null;
        }

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
            'image/png' => @imagecreatefrompng($sourcePath),
            'image/gif' => @imagecreatefromgif($sourcePath),
            'image/webp' => @imagecreatefromwebp($sourcePath),
            default => null,
        };

        if (! $image) {
            return null;
        }

        $newImage = imagecreatetruecolor($newWidth, $newHeight);

        if ($mime === 'image/png' || $mime === 'image/webp') {
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
            $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
            imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);
        }

        imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        $tempPath = sys_get_temp_dir().'/'.uniqid('img_', true).'.webp';
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

        $tempPath = sys_get_temp_dir().'/'.uniqid('og_', true).'.webp';
        imagewebp($img, $tempPath, 85);
        imagedestroy($img);

        $name = 'og-'.$slug.'-'.time().'.webp';
        $destPath = 'og-images/'.$name;
        Storage::disk('public')->put($destPath, file_get_contents($tempPath));
        @unlink($tempPath);

        return '/storage/'.$destPath;
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
        if (! file_exists($fontFile)) {
            $fontFile = resource_path('fonts/Inter-SemiBold.ttf');
        }
        if (! file_exists($fontFile)) {
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
            if (strlen($current.' '.$word) <= $maxChars) {
                $current .= ($current ? ' ' : '').$word;
            } else {
                if ($current) {
                    $lines[] = $current;
                }
                $current = $word;
            }
        }
        if ($current) {
            $lines[] = $current;
        }

        return $lines ?: [''];
    }

    /**
     * Buat gambar featured artikel persis model trimarzuki.my.id:
     * 1. Ilustrasi AI / Curated Stock Photo relevan
     * 2. Resize & kompres berulang di bawah 100 KB
     * 3. Konversi format ke WebP
     * 4. Simpan ke uploads/ai/content/[article-slug]/hero.webp
     */
    public function createArticleFeaturedImage(string $title, string $keyword = '', string $slug = ''): ?string
    {
        $directory = 'content';
        $cleanSlug = Str::slug($slug ?: $title) ?: 'article-image';
        $uploadDir = public_path('uploads/ai/'.$directory.'/'.$cleanSlug);

        if (! is_dir($uploadDir) && ! @mkdir($uploadDir, 0755, true) && ! is_dir($uploadDir)) {
            Log::warning("Could not create directory {$uploadDir}");

            return null;
        }

        $sourcePath = tempnam(sys_get_temp_dir(), 'feat-img-');
        if ($sourcePath === false) {
            return null;
        }

        try {
            $ready = false;

            // 1. Coba AI Image Generator (9Router / OpenAI / Flux)
            try {
                $ready = $this->downloadAiIllustration($title, $keyword, $sourcePath);
            } catch (\Throwable $e) {
                Log::notice('AI image provider failed, trying stock fallback: '.$e->getMessage());
            }

            // 2. Fallback Stock Image Curated (Unsplash High-Res)
            if (! $ready) {
                try {
                    $ready = $this->downloadStockFallback($title.' '.$keyword, $sourcePath);
                } catch (\Throwable $e) {
                    Log::notice('Stock fallback failed: '.$e->getMessage());
                }
            }

            // 3. Fallback Canvas Minimalis jika semua koneksi luar gagal
            if (! $ready) {
                $this->generateMinimalCanvas($title, $sourcePath);
            }

            $imageInfo = @getimagesize($sourcePath);
            $mime = strtolower((string) ($imageInfo['mime'] ?? 'image/jpeg'));

            $filename = 'hero.webp';
            $destination = $uploadDir.DIRECTORY_SEPARATOR.$filename;

            // Kompresi ketat WebP di bawah 100 KB
            $this->optimizeUnderLimit($sourcePath, $destination, $mime, 100000, 16 / 9);

            if (! is_file($destination)) {
                return null;
            }

            // Replikasi ke public disk storage Laravel agar URL storage & uploads keduanya valid
            $storageRelative = 'uploads/ai/'.$directory.'/'.$cleanSlug.'/'.$filename;
            try {
                Storage::disk('public')->put($storageRelative, file_get_contents($destination));
            } catch (\Throwable) {
                // public_path sudah terisi
            }

            return '/uploads/ai/'.$directory.'/'.$cleanSlug.'/'.$filename;
        } catch (\Throwable $e) {
            Log::error('createArticleFeaturedImage error: '.$e->getMessage());

            return null;
        } finally {
            @unlink($sourcePath);
        }
    }

    /**
     * Kompresi cerdas ke WebP dengan batasan maksimal bytes (default 100 KB).
     */
    public function optimizeUnderLimit(string $sourcePath, string $destinationPath, string $sourceMime, int $limit = 100000, ?float $targetAspectRatio = null): array
    {
        $result = $this->optimizeImageToWebp($sourcePath, $destinationPath, $sourceMime, 82, 1600, $targetAspectRatio);
        foreach ([72, 62, 52, 42, 34, 28] as $quality) {
            if (($result['file_size'] ?? PHP_INT_MAX) <= $limit) {
                break;
            }
            $result = $this->optimizeImageToWebp($sourcePath, $destinationPath, $sourceMime, $quality, 1600, $targetAspectRatio);
        }
        if (($result['file_size'] ?? PHP_INT_MAX) > $limit) {
            foreach ([1280, 1024, 800] as $edge) {
                $result = $this->optimizeImageToWebp($sourcePath, $destinationPath, $sourceMime, 30, $edge, $targetAspectRatio);
                if ($result['file_size'] <= $limit) {
                    break;
                }
            }
        }

        return $result;
    }

    public function optimizeImageToWebp(string $sourcePath, string $destinationPath, string $sourceMime, int $quality = 82, int $maxEdge = 1600, ?float $targetAspectRatio = null): array
    {
        if (! extension_loaded('gd') || ! function_exists('imagewebp')) {
            throw new \RuntimeException('WebP processing is not available. Enable PHP GD with WebP support.');
        }

        $info = @getimagesize($sourcePath);
        if ($info === false || empty($info[0]) || empty($info[1])) {
            throw new \RuntimeException('Image data could not be read.');
        }

        [$sourceWidth, $sourceHeight] = [(int) $info[0], (int) $info[1]];
        if ($sourceWidth > 8000 || $sourceHeight > 8000 || ($sourceWidth * $sourceHeight) > 32_000_000) {
            throw new \RuntimeException('Image dimensions are too large to process safely.');
        }

        $loaders = [
            'image/jpeg' => 'imagecreatefromjpeg',
            'image/png' => 'imagecreatefrompng',
            'image/gif' => 'imagecreatefromgif',
            'image/webp' => 'imagecreatefromwebp',
        ];
        $loader = $loaders[$sourceMime] ?? null;
        if ($loader === null || ! function_exists($loader)) {
            $loader = match (true) {
                str_contains($sourceMime, 'jpeg') || str_contains($sourceMime, 'jpg') => 'imagecreatefromjpeg',
                str_contains($sourceMime, 'png') => 'imagecreatefrompng',
                str_contains($sourceMime, 'webp') => 'imagecreatefromwebp',
                str_contains($sourceMime, 'gif') => 'imagecreatefromgif',
                default => null,
            };
        }
        if (! $loader || ! function_exists($loader)) {
            throw new \RuntimeException('This image format cannot be decoded: '.$sourceMime);
        }

        $source = @$loader($sourcePath);
        if (! $source) {
            throw new \RuntimeException('Image data is invalid or corrupted.');
        }

        if ($sourceMime === 'image/jpeg') {
            $source = $this->applyExifOrientation($source, $sourcePath);
        }

        if ($targetAspectRatio !== null && $targetAspectRatio > 0) {
            $source = $this->cropToAspectRatio($source, $targetAspectRatio);
        }

        $orientedWidth = imagesx($source);
        $orientedHeight = imagesy($source);
        $scale = min(1, $maxEdge / max($orientedWidth, $orientedHeight));
        $width = max(1, (int) round($orientedWidth * $scale));
        $height = max(1, (int) round($orientedHeight * $scale));

        $output = imagecreatetruecolor($width, $height);
        imagealphablending($output, false);
        imagesavealpha($output, true);
        $transparent = imagecolorallocatealpha($output, 0, 0, 0, 127);
        imagefill($output, 0, 0, $transparent);
        imagecopyresampled($output, $source, 0, 0, 0, 0, $width, $height, $orientedWidth, $orientedHeight);

        $saved = imagewebp($output, $destinationPath, max(20, min(95, $quality)));
        imagedestroy($output);
        imagedestroy($source);

        if (! $saved || ! is_file($destinationPath)) {
            throw new \RuntimeException('The optimized WebP image could not be saved.');
        }

        return [
            'width' => $width,
            'height' => $height,
            'file_size' => (int) filesize($destinationPath),
            'mime_type' => 'image/webp',
        ];
    }

    private function cropToAspectRatio($image, float $targetAspectRatio)
    {
        $width = imagesx($image);
        $height = imagesy($image);
        $currentRatio = $width / max(1, $height);

        if (abs($currentRatio - $targetAspectRatio) < 0.01 || ! function_exists('imagecrop')) {
            return $image;
        }

        if ($currentRatio > $targetAspectRatio) {
            $cropHeight = $height;
            $cropWidth = (int) round($height * $targetAspectRatio);
            $x = (int) floor(($width - $cropWidth) / 2);
            $y = 0;
        } else {
            $cropWidth = $width;
            $cropHeight = (int) round($width / $targetAspectRatio);
            $x = 0;
            $y = (int) floor(($height - $cropHeight) / 2);
        }

        $cropped = imagecrop($image, ['x' => $x, 'y' => $y, 'width' => $cropWidth, 'height' => $cropHeight]);
        if ($cropped) {
            imagedestroy($image);

            return $cropped;
        }

        return $image;
    }

    private function applyExifOrientation($image, string $sourcePath)
    {
        if (! function_exists('exif_read_data')) {
            return $image;
        }

        $exif = @exif_read_data($sourcePath);
        $orientation = (int) ($exif['Orientation'] ?? 1);
        $result = $image;

        if (in_array($orientation, [2, 4, 5, 7], true) && function_exists('imageflip')) {
            imageflip($result, in_array($orientation, [2, 5], true) ? IMG_FLIP_HORIZONTAL : IMG_FLIP_VERTICAL);
        }

        $angle = match ($orientation) {
            3, 4 => 180,
            5, 6 => -90,
            7, 8 => 90,
            default => 0,
        };

        if ($angle !== 0 && function_exists('imagerotate')) {
            $rotated = imagerotate($result, $angle, 0);
            if ($rotated) {
                imagedestroy($result);
                $result = $rotated;
            }
        }

        return $result;
    }

    private function downloadAiIllustration(string $title, string $keyword, string $destination): bool
    {
        $apiBase = SystemSetting::get('9router_api_base', 'https://9route.dari.eu.org');
        $apiKey = SystemSetting::get('9router_api_key', '');
        $model = SystemSetting::get('9router_image_model', 'cf/@cf/black-forest-labs/flux-1-schnell');

        if (empty($apiKey)) {
            $openAiKey = SystemSetting::get('openai_api_key', '');
            if (! empty($openAiKey)) {
                $apiBase = 'https://api.openai.com';
                $apiKey = $openAiKey;
                $model = 'dall-e-3';
            }
        }

        if (empty($apiKey)) {
            return false;
        }

        $topic = trim($title.' '.$keyword);
        $visualConcept = 'A clear editorial visual metaphor for '.($keyword ?: $title).', showing one central subject with a structured supporting system around it.';
        $mainSubject = 'the central business or technology concept from the topic, presented as a polished simplified diagram or editorial object';
        $masterPrompt = SystemSetting::get('9router_image_master_prompt') ?: <<<'PROMPT'
Create a professional editorial technical illustration for a web development and SEO blog.

Topic: {topic}

Visual concept:
{visual_concept}

Main subject:
{main_subject}

Style:
clean editorial technology illustration, structured composition, premium SaaS visual language,
subtle depth, realistic but simplified, professional and trustworthy.

Brand direction:
dark navy background #15253F, warm orange accent #FF6B35, gold highlights #CE9A45,
cream secondary tones, high contrast, restrained color palette, consistent Juki web identity.

Composition:
16:9 landscape, clear focal subject centered or positioned right, generous negative space,
suitable for a blog hero image, no clutter, consistent framing across the article library.

Do not include:
unreadable text, random letters, fake logos, watermarks, excessive neon,
generic stock-photo style, distorted UI elements, long text inside the image.
PROMPT;
        $prompt = str_replace(
            ['{topic}', '{visual_concept}', '{main_subject}'],
            [$topic, $visualConcept, $mainSubject],
            $masterPrompt
        );

        $endpoint = rtrim($apiBase, '/').(str_ends_with($apiBase, '/v1') ? '/images/generations' : '/v1/images/generations');

        $headers = [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer '.$apiKey,
            'User-Agent: SEOFAST/1.0',
        ];

        $payload = json_encode([
            'model' => $model,
            'prompt' => $prompt,
            'n' => 1,
            'size' => SystemSetting::get('9router_image_size', '1792x1024'),
            'response_format' => 'b64_json',
        ]);

        $ch = curl_init($endpoint);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);
        $raw = curl_exec($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($raw === false || $status < 200 || $status >= 300) {
            return false;
        }

        $data = json_decode((string) $raw, true);
        $b64 = $data['data'][0]['b64_json'] ?? '';
        $bytes = false;

        if (! empty($b64)) {
            $bytes = base64_decode($b64, true);
        } elseif (! empty($data['data'][0]['url'])) {
            $imgUrl = (string) $data['data'][0]['url'];
            $imgCh = curl_init($imgUrl);
            curl_setopt_array($imgCh, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_CONNECTTIMEOUT => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_SSL_VERIFYPEER => true,
            ]);
            $bytes = curl_exec($imgCh);
            curl_close($imgCh);
        }

        return $bytes !== false && strlen($bytes) > 200 && file_put_contents($destination, $bytes) !== false;
    }

    private function downloadStockFallback(string $context, string $destination): bool
    {
        $context = strtolower($context);
        $images = [
            'ai' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?w=1600&q=85',
            'seo' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=1600&q=85',
            'server' => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=1600&q=85',
            'network' => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=1600&q=85',
            'security' => 'https://images.unsplash.com/photo-1563013544-824ae1b704d3?w=1600&q=85',
            'website' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=1600&q=85',
            'web' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=1600&q=85',
            'computer' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=1600&q=85',
            'code' => 'https://images.unsplash.com/photo-1461749280684-dccba630e2f6?w=1600&q=85',
            'coding' => 'https://images.unsplash.com/photo-1461749280684-dccba630e2f6?w=1600&q=85',
            'google' => 'https://images.unsplash.com/photo-1573804633927-bfcbcd909acd?w=1600&q=85',
            'cloud' => 'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?w=1600&q=85',
            'data' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=1600&q=85',
            'business' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=1600&q=85',
            'automation' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?w=1600&q=85',
        ];

        $selectedUrl = $images['website'];
        foreach ($images as $keyword => $candidate) {
            if (str_contains($context, $keyword)) {
                $selectedUrl = $candidate;
                break;
            }
        }

        $ch = curl_init($selectedUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 3,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 25,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        ]);
        $bytes = curl_exec($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($bytes === false || $status < 200 || $status >= 300 || strlen($bytes) < 500) {
            return false;
        }

        return file_put_contents($destination, $bytes) !== false;
    }

    private function generateMinimalCanvas(string $title, string $destination): bool
    {
        $w = 1200;
        $h = 630;
        $img = imagecreatetruecolor($w, $h);

        $c1 = imagecolorallocate($img, 21, 37, 63);
        $c2 = imagecolorallocate($img, 15, 29, 50);
        $this->drawGradient($img, $w, $h, $c1, $c2);

        // Keep the emergency fallback aligned with the master style and never render text.
        $orange = imagecolorallocate($img, 255, 107, 53);
        $gold = imagecolorallocate($img, 206, 154, 69);
        $line = imagecolorallocatealpha($img, 255, 255, 255, 82);
        $centerX = 820;
        $centerY = 315;

        for ($i = 0; $i < 5; $i++) {
            $x = 230 + ($i * 145);
            $y = 130 + (($i % 2) * 260);
            imageline($img, $x, $y, $centerX, $centerY, $line);
            imageellipse($img, $x, $y, 74, 74, $gold);
            imagefilledellipse($img, $x, $y, 20, 20, $orange);
        }

        imagefilledellipse($img, $centerX, $centerY, 170, 170, $orange);
        imageellipse($img, $centerX, $centerY, 215, 215, $gold);
        for ($i = 0; $i < 4; $i++) {
            imagearc($img, 970, 120 + ($i * 115), 130, 130, 20, 310, $gold);
        }

        imagepng($img, $destination);
        imagedestroy($img);

        return true;
    }
}

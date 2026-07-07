<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class ImageSearchController extends Controller
{
    public function index(Content $content)
    {
        return view('admin.content.images', compact('content'));
    }

    public function search(Request $request, Content $content)
    {
        $query = $request->input('query', $content->target_keyword);

        $images = $this->searchOpenverse($query);

        if (empty($images)) {
            $images = $this->searchWikimedia($query);
        }

        if (empty($images)) {
            $images = $this->loremflickrFallback($query);
        }

        return response()->json(['images' => $images]);
    }

    // ---------------------------------------------------------------
    //  HTTP HELPER — native cURL, no Guzzle needed
    // ---------------------------------------------------------------

    private function httpGet(string $url, array $headers = [], array $query = []): ?string
    {
        if (!empty($query)) {
            $url .= (str_contains($url, '?') ? '&' : '?') . http_build_query($query);
        }

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT        => 15,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_USERAGENT      => $headers['User-Agent'] ?? 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            CURLOPT_HTTPHEADER     => $this->buildHeaders($headers),
        ]);

        $body = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return ($httpCode >= 200 && $httpCode < 300 && $body !== false) ? $body : null;
    }

    private function httpPost(string $url, array $payload, array $headers = []): ?string
    {
        $ch = curl_init();
        $h = [];
        foreach ($headers as $k => $v) {
            if ($k !== 'User-Agent') $h[] = "$k: $v";
        }
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT        => 60,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_USERAGENT      => $headers['User-Agent'] ?? 'SEOFAST/1.0',
            CURLOPT_HTTPHEADER     => $h,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($payload),
        ]);
        $body = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return ($httpCode >= 200 && $httpCode < 300 && $body !== false) ? $body : null;
    }

    private function buildHeaders(array $headers): array
    {
        $lines = [];
        foreach ($headers as $key => $val) {
            if ($key !== 'User-Agent') {
                $lines[] = "$key: $val";
            }
        }
        return $lines;
    }

    // ---------------------------------------------------------------
    //  METHOD 1: Openverse (CC Search)
    // ---------------------------------------------------------------

    private function searchOpenverse(string $query): array
    {
        try {
            $json = $this->httpGet('https://api.openverse.engineering/v1/images/', [
                'User-Agent' => 'SEOFAST/1.0',
            ], [
                'q'         => $query,
                'page_size' => 24,
                'license'   => 'cc0,pdm',
                'mature'    => 'false',
            ]);

            if (!$json) return [];

            $data = json_decode($json, true);
            $results = $data['results'] ?? [];

            if (empty($results)) return [];

            $images = [];
            foreach ($results as $idx => $img) {
                $url   = $img['url'] ?? ($img['thumbnail'] ?? '');
                $thumb = $img['thumbnail'] ?? $url;
                if (!$url) continue;

                $images[] = [
                    'id'       => 'openv_' . ($idx + 1),
                    'url'      => $url,
                    'thumb'    => $thumb,
                    'author'   => $img['creator'] ?? ($img['source'] ?? 'Openverse'),
                    'alt_text' => $img['title'] ?? $query,
                ];
                if (count($images) >= 24) break;
            }

            return $images;
        } catch (\Exception $e) {
            return [];
        }
    }

    // ---------------------------------------------------------------
    //  METHOD 2: Wikimedia Commons
    // ---------------------------------------------------------------

    private function searchWikimedia(string $query): array
    {
        try {
            $json = $this->httpGet('https://commons.wikimedia.org/w/api.php', [
                'User-Agent' => 'SEOFAST/1.0',
            ], [
                'action'        => 'query',
                'generator'     => 'search',
                'gsrsearch'     => $query,
                'gsrnamespace'  => 6,
                'gsrlimit'      => 24,
                'prop'          => 'imageinfo',
                'iiprop'        => 'url|extmetadata',
                'format'        => 'json',
            ]);

            if (!$json) return [];

            $data = json_decode($json, true);
            $pages = $data['query']['pages'] ?? [];

            if (empty($pages)) return [];

            $images = [];
            foreach ($pages as $page) {
                $info = $page['imageinfo'][0] ?? null;
                if (!$info) continue;

                $url   = $info['url'] ?? '';
                $thumb = $info['thumburl'] ?? $url;
                $title = $page['title'] ?? '';

                if (!$url) continue;

                $author = $info['extmetadata']['Artist']['value']
                    ?? $info['extmetadata']['Credit']['value']
                    ?? 'Wikimedia Commons';

                $images[] = [
                    'id'       => 'wiki_' . (count($images) + 1),
                    'url'      => $url,
                    'thumb'    => $thumb,
                    'author'   => is_string($author) ? strip_tags($author) : 'Wikimedia Commons',
                    'alt_text' => $title ? str_replace(['File:', '_'], ['', ' '], $title) : $query,
                ];
                if (count($images) >= 24) break;
            }

            return $images;
        } catch (\Exception $e) {
            return [];
        }
    }

    // ---------------------------------------------------------------
    //  FALLBACK: loremflickr
    // ---------------------------------------------------------------

    private function loremflickrFallback(string $query): array
    {
        $images = [];
        for ($i = 0; $i < 12; $i++) {
            $images[] = [
                'id'       => 'img_' . rand(1000, 9999),
                'url'      => 'https://loremflickr.com/800/600/' . urlencode(str_replace(' ', ',', $query)) . '?lock=' . rand(1, 1000),
                'thumb'    => 'https://loremflickr.com/400/300/' . urlencode(str_replace(' ', ',', $query)) . '?lock=' . rand(1, 1000),
                'author'   => 'Free Provider',
                'alt_text' => $query . ' image ' . ($i + 1),
            ];
        }
        return $images;
    }

    // ---------------------------------------------------------------
    //  METHOD 3: AI Illustration Generation
    // ---------------------------------------------------------------

    public function generateAiIllustration(Request $request, Content $content)
    {
        $request->validate([
            'prompt' => 'nullable|string|max:500',
        ]);

        $query = $request->input('prompt', $content->target_keyword);

        try {
            $imageUrl = $this->callImageGenerationApi($query);

            if (!$imageUrl) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghasilkan ilustrasi. Coba lagi dengan prompt berbeda.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'image'   => [
                    'id'       => 'ai_' . rand(1000, 9999),
                    'url'      => $imageUrl,
                    'thumb'    => $imageUrl,
                    'author'   => 'AI Generated',
                    'alt_text' => $query,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'AI illustration error: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function callImageGenerationApi(string $prompt): ?string
    {
        $apiBase = SystemSetting::get('9router_api_base', 'https://9route.dari.eu.org');
        $apiKey  = SystemSetting::get('9router_api_key', '');
        $model   = SystemSetting::get('9router_image_model', 'cf/@cf/black-forest-labs/flux-1-schnell');

        $url = rtrim($apiBase, '/') . '/v1/images/generations';

        $headers = [
            'User-Agent' => 'SEOFAST/1.0',
            'Content-Type' => 'application/json',
        ];

        if (!empty($apiKey)) {
            $headers['Authorization'] = 'Bearer ' . $apiKey;
        }

        $payload = [
            'model'   => $model,
            'prompt'  => 'Illustration style, flat vector, minimalist design, max 5 colors, clean lines, no detailed human faces, simple shapes, modern look: ' . $prompt,
            'n'       => 1,
        ];

        $body = $this->httpPost($url, $payload, $headers);

        if (!$body) {
            return null;
        }

        $data = json_decode($body, true);
        $imageUrl = $data['data'][0]['url'] ?? null;
        $b64 = $data['data'][0]['b64_json'] ?? null;

        if ($imageUrl) {
            $result = app(\App\Services\ImageService::class)->downloadAndProcess(
                $imageUrl, 'images', 1200
            );
            return $result ? $result['url'] : $imageUrl;
        }

        if ($b64) {
            return $this->saveBase64Image($b64);
        }

        return null;
    }

    private function saveBase64Image(string $b64): string
    {
        $decoded = base64_decode($b64);
        if ($decoded === false) {
            return 'data:image/png;base64,' . $b64;
        }

        $tmp = tempnam(sys_get_temp_dir(), 'b64_');
        file_put_contents($tmp, $decoded);

        $result = app(\App\Services\ImageService::class)->compressToWebp($tmp, 80, 1200);
        @unlink($tmp);

        if ($result) {
            $name = 'ai_' . uniqid() . '.webp';
            $path = 'images/' . $name;
            \Illuminate\Support\Facades\Storage::disk('public')->put($path, file_get_contents($result));
            @unlink($result);
            return \Illuminate\Support\Facades\Storage::disk('public')->url($path);
        }

        // Fallback: save as-is
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_buffer($finfo, $decoded);
        finfo_close($finfo);

        $ext = match ($mime) {
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
            'image/gif'  => 'gif',
            default      => 'png',
        };

        $filename = 'ai_illustration_' . uniqid() . '.' . $ext;
        $path = 'images/' . $filename;
        try {
            \Illuminate\Support\Facades\Storage::disk('public')->put($path, $decoded);
            return \Illuminate\Support\Facades\Storage::disk('public')->url($path);
        } catch (\Exception $e) {
            return 'data:image/png;base64,' . $b64;
        }
    }

    public function select(Request $request, Content $content)
    {
        $request->validate([
            'image_url' => 'required|url',
            'alt_text'  => 'nullable|string|max:255',
        ]);

        $result = app(\App\Services\ImageService::class)->downloadAndProcess(
            $request->image_url, 'content-images', 1200
        );

        $content->update([
            'featured_image_url' => $result ? $result['url'] : $request->image_url,
            'featured_image_alt' => $request->alt_text ?: $content->target_keyword,
        ]);

        return redirect()->route('admin.content.edit', $content->id)
            ->with('success', 'Featured image successfully attached!');
    }
}

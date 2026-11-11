<?php

namespace App\Services;

use App\Models\Content;
use App\Models\Page;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IndexNowService
{
    /**
     * Get or create IndexNow API verification key.
     */
    public function getKey(): string
    {
        $key = SystemSetting::get('indexnow_api_key');
        if (empty($key)) {
            $key = substr(md5(config('app.key', 'seofast-juki') . 'indexnow-key'), 0, 32);
            SystemSetting::set('indexnow_api_key', $key);
        }

        return $key;
    }

    /**
     * Submit single or multiple URLs to IndexNow (Bing, Yandex, Seznam, Naver) and ping RSS/Google.
     */
    public function submit(string|array $urls): array
    {
        $urlList = is_array($urls) ? array_values(array_unique($urls)) : [$urls];
        if (empty($urlList)) {
            return ['status' => 'empty', 'submitted' => 0];
        }

        $key = $this->getKey();
        $sampleUrl = $urlList[0];
        $host = parse_url($sampleUrl, PHP_URL_HOST) ?: parse_url(config('app.url'), PHP_URL_HOST) ?: 'juki.eu.org';
        $keyLocation = url('/' . $key . '.txt');

        $payload = [
            'host' => $host,
            'key' => $key,
            'keyLocation' => $keyLocation,
            'urlList' => $urlList,
        ];

        $results = [];

        // 1. IndexNow API Aggregator (Disseminates to Bing, Yandex, Seznam, etc.)
        try {
            $response = Http::timeout(10)
                ->asJson()
                ->post('https://api.indexnow.org/indexnow', $payload);
            $results['indexnow'] = $response->status();
        } catch (\Throwable $e) {
            $results['indexnow_error'] = $e->getMessage();
            Log::warning('IndexNow api.indexnow.org submission error: ' . $e->getMessage());
        }

        // 2. Direct Bing IndexNow endpoint fallback
        try {
            $responseBing = Http::timeout(10)
                ->asJson()
                ->post('https://www.bing.com/indexnow', $payload);
            $results['bing'] = $responseBing->status();
        } catch (\Throwable $e) {
            $results['bing_error'] = $e->getMessage();
        }

        // 3. Ping Google Sitemap
        try {
            $sitemapUrl = url('/sitemap.xml');
            Http::timeout(5)->get('https://www.google.com/ping?sitemap=' . urlencode($sitemapUrl));
            $results['google_sitemap_ping'] = true;
        } catch (\Throwable $e) {
            $results['google_sitemap_ping'] = false;
        }

        // 4. Ping PubSubHubbub / WebSub for instant RSS feed crawling
        try {
            $feedUrl = url('/feed');
            Http::asForm()->timeout(5)->post('https://pubsubhubbub.appspot.com/', [
                'hub.mode' => 'publish',
                'hub.url' => $feedUrl,
            ]);
            $results['pubsubhubbub'] = true;
        } catch (\Throwable $e) {
            $results['pubsubhubbub'] = false;
        }

        Log::info('IndexNow & RSS Ping dispatched for ' . count($urlList) . ' URLs', [
            'urls' => $urlList,
            'results' => $results,
        ]);

        return [
            'status' => 'success',
            'submitted' => count($urlList),
            'results' => $results,
        ];
    }

    /**
     * Submit blog post on publish.
     */
    public function submitPost(Content $content): array
    {
        $url = url('/blog/' . $content->slug);
        return $this->submit($url);
    }

    /**
     * Submit static or landing page on publish.
     */
    public function submitPage(Page $page): array
    {
        $url = url('/' . ltrim($page->slug, '/'));
        return $this->submit($url);
    }
}

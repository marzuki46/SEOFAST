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
     * Default update services. Providers can be replaced from Admin Settings.
     * Keep one endpoint per line so unavailable legacy services can be removed
     * without a code deployment.
     */
    public static function defaultProvidersText(): string
    {
        return implode("\n", [
            'https://pubsubhubbub.appspot.com/publish',
            'https://pubsubhubbub.superfeedr.com/',
            'https://websubhub.com/hub',
            'https://rpc.pingomatic.com/',
            'https://ping.blo.gs/',
            'https://rpc.weblogs.com/RPC2',
            'https://api.my.yahoo.com/RPC2',
            'https://ping.feedburner.com/',
            'https://rpc.twingly.com/',
            'https://ping.bloggers.jp/rpc/',
            'https://ping.fc2.com/',
            'https://rpc.bloggerei.de/ping/',
        ]);
    }

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

        // 4. Notify configured WebSub and XML-RPC update services.
        $results['update_services'] = $this->notifyUpdateServices($sampleUrl);

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
     * Notify WebSub hubs and legacy XML-RPC services that the RSS feed changed.
     * This method is called from a queue job so external services never block
     * the request that publishes an article.
     */
    public function notifyUpdateServices(string $contentUrl): array
    {
        $feedUrl = url('/feed');
        $homeUrl = url('/');
        $providers = (string) SystemSetting::get('rss_ping_providers', self::defaultProvidersText());
        $endpoints = collect(preg_split('/\R+/', $providers) ?: [])
            ->map(static fn (string $endpoint): string => trim($endpoint))
            ->filter(static fn (string $endpoint): bool => $endpoint !== '')
            ->unique()
            ->values();

        $results = [];
        foreach ($endpoints as $endpoint) {
            if (! $this->isSafeProviderUrl($endpoint)) {
                $results[$endpoint] = 'rejected';
                continue;
            }

            try {
                if ($this->isWebSubEndpoint($endpoint)) {
                    $response = Http::asForm()
                        ->connectTimeout(3)
                        ->timeout(5)
                        ->retry(1, 100, throw: false)
                        ->post($endpoint, [
                            'hub.mode' => 'publish',
                            'hub.url' => $feedUrl,
                        ]);
                } elseif (str_contains(strtolower($endpoint), 'feedburner')) {
                    $response = Http::connectTimeout(3)
                        ->timeout(5)
                        ->retry(1, 100, throw: false)
                        ->get($endpoint, ['url' => $feedUrl]);
                } else {
                    $response = Http::withHeaders([
                            'Content-Type' => 'text/xml',
                            'User-Agent' => config('app.name', 'SEOFAST') . ' RSS notifier',
                        ])
                        ->connectTimeout(3)
                        ->timeout(5)
                        ->retry(1, 100, throw: false)
                        ->withBody($this->xmlRpcPingBody($homeUrl, $feedUrl), 'text/xml')
                        ->post($endpoint);
                }

                $results[$endpoint] = $response->status();
            } catch (\Throwable $e) {
                $results[$endpoint] = 'failed';
                Log::warning('RSS update service notification failed', [
                    'endpoint' => $endpoint,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        Log::info('RSS/WebSub/XML-RPC update services notified', [
            'content_url' => $contentUrl,
            'providers' => count($results),
        ]);

        return $results;
    }

    private function isWebSubEndpoint(string $endpoint): bool
    {
        $lower = strtolower($endpoint);

        return str_contains($lower, 'pubsubhubbub')
            || str_contains($lower, 'superfeedr')
            || str_contains($lower, 'websubhub')
            || str_ends_with(parse_url($endpoint, PHP_URL_PATH) ?: '', '/publish');
    }

    private function isSafeProviderUrl(string $endpoint): bool
    {
        $parts = parse_url($endpoint);
        if (! is_array($parts) || ! in_array(strtolower($parts['scheme'] ?? ''), ['http', 'https'], true)) {
            return false;
        }

        return ! empty($parts['host']) && empty($parts['user']) && empty($parts['pass']);
    }

    private function xmlRpcPingBody(string $homeUrl, string $feedUrl): string
    {
        $value = static fn (string $value): string => '<value><string>' . htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8') . '</string></value>';

        return '<?xml version="1.0"?>'
            . '<methodCall><methodName>weblogUpdates.extendedPing</methodName><params>'
            . '<param>' . $value(config('app.name', 'Website')) . '</param>'
            . '<param>' . $value($homeUrl) . '</param>'
            . '<param>' . $value($homeUrl) . '</param>'
            . '<param>' . $value($feedUrl) . '</param>'
            . '</params></methodCall>';
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

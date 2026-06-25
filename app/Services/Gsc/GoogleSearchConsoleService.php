<?php

namespace App\Services\Gsc;

use App\Models\TenantApiCredential;
use App\Models\GscSyncLog;
use App\Models\GscUrlInspection;
use App\Models\GscSearchAnalytics;
use App\Models\Content;
use Google\Client;
use Google\Service\SearchConsole;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Carbon;

class GoogleSearchConsoleService
{
    private Client $client;
    private int $tenantId;
    private TenantApiCredential $credential;

    public function __construct(int $tenantId)
    {
        $this->tenantId = $tenantId;
        $this->credential = TenantApiCredential::where('tenant_id', $tenantId)
            ->where('service', 'google_search_console')
            ->where('is_active', true)
            ->firstOrFail();

        $this->client = $this->buildAuthenticatedClient();
    }

    /**
     * URL Inspection — Cek status satu URL di GSC
     * Quota: 2000 requests/day per property
     */
    public function inspectUrl(Content $content, GscSyncLog $syncLog): GscUrlInspection
    {
        $searchConsole = new SearchConsole($this->client);

        $requestBody = new SearchConsole\InspectUrlIndexRequest([
            'inspectionUrl'   => $this->buildFullUrl($content->slug),
            'siteUrl'         => $this->credential->property_url,
            'languageCode'    => 'id',
        ]);

        $response = $searchConsole->urlInspection_index->inspect($requestBody);
        $result   = $response->getInspectionResult();
        $indexStatus = $result->getIndexStatusResult();
        $mobile   = $result->getMobileUsabilityResult();
        $rich     = $result->getRichResultsResult();

        return GscUrlInspection::updateOrCreate(
            [
                'tenant_id'       => $this->tenantId,
                'content_id'      => $content->id,
                'gsc_sync_log_id' => $syncLog->id,
            ],
            [
                'verdict'                         => $indexStatus->getVerdict(),
                'coverage_state'                  => $indexStatus->getCoverageState(),
                'robots_txt_state'                => $indexStatus->getRobotsTxtState(),
                'indexing_state'                  => $indexStatus->getIndexingState(),
                'page_fetch_state'                => $indexStatus->getPageFetchState(),
                'crawled_as_mobile'               => $indexStatus->getCrawledAs() === 'MOBILE',
                'last_crawl_time'                 => $indexStatus->getLastCrawlTime()
                    ? Carbon::parse($indexStatus->getLastCrawlTime()) : null,
                'canonical_declared_in_page'      => $indexStatus->getPageClassification()
                    ?->getCanonicalUrl() ?? null,
                'canonical_selected_by_google'    => $indexStatus->getGoogleCanonical(),
                'mobile_usability_verdict'        => $mobile?->getVerdict(),
                'mobile_usability_issues'         => $mobile?->getIssues() ?? [],
                'rich_results_verdict'            => $rich?->getVerdict(),
                'rich_results_items'              => $rich?->getDetectedItems() ?? [],
                'raw_api_response'                => json_decode(json_encode($result), true),
                'inspected_at'                    => now(),
            ]
        );
    }

    /**
     * Search Analytics — Tarik data klik/impresi/posisi per query
     * Quota: 1200 requests/minute, data tersedia dengan delay 3-4 hari
     */
    public function fetchSearchAnalytics(
        string $startDate,
        string $endDate,
        int $tenantId
    ): array {
        $searchConsole = new SearchConsole($this->client);

        $request = new SearchConsole\SearchAnalyticsQueryRequest([
            'startDate'  => $startDate,  // format: 'YYYY-MM-DD'
            'endDate'    => $endDate,
            'dimensions' => ['query', 'page', 'country', 'device'],
            'rowLimit'   => 25000,       // Maksimum per request
            'dataState'  => 'final',     // final | all (final = data lengkap)
        ]);

        $response = $searchConsole->searchanalytics->query(
            $this->credential->property_url,
            $request
        );

        $rows    = $response->getRows() ?? [];
        $upserts = [];

        foreach ($rows as $row) {
            $keys = $row->getKeys();      // [query, page, country, device]
            if (count($keys) < 4) continue;
            [$query, $pageUrl, $country, $device] = $keys;

            // Temukan content_id dari URL
            $slug      = $this->extractSlug($pageUrl);
            $contentId = Content::where('tenant_id', $tenantId)
                ->where('slug', $slug)
                ->value('id');

            if (!$contentId) continue;

            $upserts[] = [
                'tenant_id'   => $tenantId,
                'content_id'  => $contentId,
                'query'       => $query,
                'page_url'    => $pageUrl,
                'country'     => strtolower($country),
                'device'      => strtolower($device),
                'data_date'   => $endDate,
                'clicks'      => (int) $row->getClicks(),
                'impressions' => (int) $row->getImpressions(),
                'ctr'         => round($row->getCtr(), 6),
                'position'    => round($row->getPosition(), 2),
                'synced_at'   => now(),
            ];
        }

        // Batch upsert untuk performa
        foreach (array_chunk($upserts, 500) as $chunk) {
            GscSearchAnalytics::upsert(
                $chunk,
                ['tenant_id', 'content_id', 'query', 'country', 'device', 'data_date'],
                ['clicks', 'impressions', 'ctr', 'position', 'synced_at']
            );
        }

        return [
            'total_rows'   => count($rows),
            'saved_rows'   => count($upserts),
            'skipped_rows' => count($rows) - count($upserts),
        ];
    }

    /**
     * Submit URL ke Google Indexing API
     * Membutuhkan Service Account (bukan OAuth user biasa)
     * Quota: 200 requests/day per property
     */
    public function submitToIndexingApi(array $urls): array
    {
        $serviceAccountCred = TenantApiCredential::where('tenant_id', $this->tenantId)
            ->where('service', 'google_indexing_api')
            ->firstOrFail();

        $serviceAccount = json_decode(
            Crypt::decryptString($serviceAccountCred->service_account_json),
            true
        );

        $client = new Client();
        $client->setAuthConfig($serviceAccount);
        $client->addScope('https://www.googleapis.com/auth/indexing');
        $client->fetchAccessTokenWithAssertion();

        $accessToken = $client->getAccessToken()['access_token'];
        $results     = [];

        foreach ($urls as $url) {
            $response = \Illuminate\Support\Facades\Http::withToken($accessToken)
                ->post('https://indexing.googleapis.com/v3/urlNotifications:publish', [
                    'url'  => $url,
                    'type' => 'URL_UPDATED',  // URL_UPDATED | URL_DELETED
                ]);

            $results[$url] = [
                'success'       => $response->successful(),
                'http_status'   => $response->status(),
                'response_body' => $response->json(),
            ];

            // Throttle — max 100 req/menit
            usleep(600000); // 0.6 detik
        }

        return $results;
    }

    private function buildAuthenticatedClient(): Client
    {
        $client = new Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));

        $accessTokenRaw = $this->credential->access_token;
        try {
            $accessToken = Crypt::decryptString($accessTokenRaw);
        } catch (\Exception $e) {
            $accessToken = $accessTokenRaw;
        }

        $tokenData = json_decode($accessToken, true) ?? $accessToken;

        // Auto-refresh jika hampir expired
        if ($this->credential->token_expires_at?->subMinutes(5)->isPast()) {
            $refreshTokenRaw = $this->credential->refresh_token;
            try {
                $refreshToken = Crypt::decryptString($refreshTokenRaw);
            } catch (\Exception $e) {
                $refreshToken = $refreshTokenRaw;
            }

            $client->setAccessToken(['refresh_token' => $refreshToken]);
            $newToken = $client->fetchAccessTokenWithRefreshToken();

            $this->credential->update([
                'access_token'     => Crypt::encryptString($newToken['access_token']),
                'token_expires_at' => now()->addSeconds($newToken['expires_in']),
            ]);

            $tokenData = $newToken['access_token'];
        }

        $client->setAccessToken($tokenData);
        return $client;
    }

    private function buildFullUrl(string $slug): string
    {
        $domain = \App\Models\Tenant::find($this->tenantId)->domain;
        return "https://{$domain}/{$slug}";
    }

    private function extractSlug(string $pageUrl): string
    {
        $path = parse_url($pageUrl, PHP_URL_PATH);
        return ltrim($path, '/');
    }
}

<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\TenantApiCredential;
use Google\Client as GoogleClient;
use Google\Service\Webmasters;
use Google\Service\Webmasters\SearchAnalyticsQueryRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GSCService
{
    private ?GoogleClient $client = null;
    private ?Webmasters $webmasters = null;

    /**
     * Initialize Google Client with tenant credentials.
     */
    public function initialize(Tenant $tenant): bool
    {
        try {
            $credentials = $tenant->apiCredentials()
                ->forService('google_search_console')
                ->first();

            if (!$credentials || !$credentials->service_account_json) {
                Log::warning("No GSC credentials found for tenant: {$tenant->id}");
                return false;
            }

            $this->client = new GoogleClient();
            $this->client->setAuthConfig(json_decode($credentials->service_account_json, true));
            $this->client->addScope(Webmasters::WEBMASTERS_READONLY);
            $this->client->setAccessType('offline');
            $this->client->setPrompt('select_account consent');

            // Load stored access token if exists
            if ($credentials->access_token) {
                $this->client->setAccessToken($credentials->access_token);
            }

            // Refresh if expired
            if ($this->client->isAccessTokenExpired()) {
                $newToken = $this->client->fetchAccessTokenWithAssertion();
                $credentials->update([
                    'access_token' => json_encode($newToken),
                    'token_expires_at' => now()->addSeconds($newToken['expires_in'] ?? 3600),
                ]);
                $this->client->setAccessToken($newToken);
            }

            $this->webmasters = new Webmasters($this->client);
            return true;

        } catch (\Exception $e) {
            Log::error("GSC initialization failed: {$e->getMessage()}", [
                'tenant_id' => $tenant->id,
                'trace' => $e->getTraceAsString(),
            ]);
            return false;
        }
    }

    /**
     * Fetch search analytics data from GSC.
     */
    public function getSearchAnalytics(
        string $siteUrl,
        \DateTime $startDate,
        \DateTime $endDate,
        array $dimensions = ['query', 'page', 'country', 'device'],
        int $rowLimit = 1000
    ): array {
        if (!$this->webmasters) {
            throw new \RuntimeException('GSC not initialized. Call initialize() first.');
        }

        $cacheKey = "gsc_analytics_{$siteUrl}_{$startDate->format('Ymd')}_{$endDate->format('Ymd')}_" . md5(implode(',', $dimensions));

        return Cache::remember($cacheKey, 3600, function () use ($siteUrl, $startDate, $endDate, $dimensions, $rowLimit) {
            $request = new SearchAnalyticsQueryRequest();
            $request->setStartDate($startDate->format('Y-m-d'));
            $request->setEndDate($endDate->format('Y-m-d'));
            $request->setDimensions($dimensions);
            $request->setRowLimit($rowLimit);

            try {
                $response = $this->webmasters->searchanalytics->query($siteUrl, $request);
                $rows = $response->getRows();

                if (empty($rows)) {
                    return [];
                }

                return array_map(function ($row) {
                    $data = [
                        'clicks' => $row->getClicks(),
                        'impressions' => $row->getImpressions(),
                        'ctr' => $row->getCtr(),
                        'position' => $row->getPosition(),
                    ];

                    $keys = $row->getKeys();
                    if (isset($keys[0])) $data['query'] = $keys[0];
                    if (isset($keys[1])) $data['page'] = $keys[1];
                    if (isset($keys[2])) $data['country'] = $keys[2];
                    if (isset($keys[3])) $data['device'] = $keys[3];

                    return $data;
                }, $rows);

            } catch (\Exception $e) {
                Log::error("GSC API query failed: {$e->getMessage()}", [
                    'site_url' => $siteUrl,
                ]);
                return [];
            }
        });
    }

    /**
     * Verify site ownership in GSC.
     */
    public function verifySite(string $siteUrl): bool
    {
        if (!$this->webmasters) {
            throw new \RuntimeException('GSC not initialized.');
        }

        try {
            $sites = $this->webmasters->sites->listSites();
            foreach ($sites->getSiteEntry() as $site) {
                if ($site->getSiteUrl() === $siteUrl) {
                    return true;
                }
            }
            return false;
        } catch (\Exception $e) {
            Log::error("GSC site verification failed: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * List all verified sites for the tenant.
     */
    public function listSites(): array
    {
        if (!$this->webmasters) {
            throw new \RuntimeException('GSC not initialized.');
        }

        try {
            $sites = $this->webmasters->sites->listSites();
            return array_map(function ($site) {
                return [
                    'url' => $site->getSiteUrl(),
                    'permission_level' => $site->getPermissionLevel(),
                ];
            }, $sites->getSiteEntry());
        } catch (\Exception $e) {
            Log::error("GSC list sites failed: {$e->getMessage()}");
            return [];
        }
    }
}
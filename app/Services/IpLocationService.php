<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class IpLocationService
{
    public function lookup(string $ip): array
    {
        if (! filter_var($ip, FILTER_VALIDATE_IP) || $this->isPrivate($ip)) {
            return ['ip' => $ip, 'status' => 'private_or_invalid'];
        }

        return Cache::remember('ip-location:'.$ip, now()->addDay(), function () use ($ip): array {
            try {
                $response = Http::acceptJson()->connectTimeout(1)->timeout(3)
                    ->get('https://ipwho.is/'.rawurlencode($ip));
                if (! $response->successful() || $response->json('success') === false) {
                    return ['ip' => $ip, 'status' => 'unavailable'];
                }

                return [
                    'ip' => $ip,
                    'status' => 'ok',
                    'country' => $response->json('country'),
                    'country_code' => $response->json('country_code'),
                    'region' => $response->json('region'),
                    'city' => $response->json('city'),
                    'latitude' => $response->json('latitude'),
                    'longitude' => $response->json('longitude'),
                    'isp' => $response->json('connection.isp'),
                    'org' => $response->json('connection.org'),
                ];
            } catch (\Throwable) {
                return ['ip' => $ip, 'status' => 'unavailable'];
            }
        });
    }

    private function isPrivate(string $ip): bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false;
    }
}

<?php

namespace App\Models\Traits;

use App\Models\TenantApiCredential;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasApiCredentials
{
    /**
     * Get all API credentials for this tenant.
     */
    public function apiCredentials(): HasMany
    {
        return $this->hasMany(TenantApiCredential::class);
    }

    /**
     * Get credentials for a specific service.
     */
    public function getApiCredential(string $service): ?TenantApiCredential
    {
        return $this->apiCredentials()->forService($service)->first();
    }

    /**
     * Store or update API credentials for a service.
     */
    public function setApiCredential(
        string $service,
        ?string $accessToken = null,
        ?string $refreshToken = null,
        ?string $serviceAccountJson = null,
        ?string $propertyUrl = null,
        ?int $expiresIn = null
    ): TenantApiCredential {
        $data = array_filter([
            'service' => $service,
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'service_account_json' => $serviceAccountJson,
            'property_url' => $propertyUrl,
            'is_active' => true,
        ], fn ($value) => !is_null($value));

        if ($expiresIn) {
            $data['token_expires_at'] = now()->addSeconds($expiresIn);
        }

        return $this->apiCredentials()->updateOrCreate(
            ['service' => $service],
            $data
        );
    }

    /**
     * Revoke API credentials for a service.
     */
    public function revokeApiCredential(string $service): bool
    {
        return $this->apiCredentials()
            ->where('service', $service)
            ->update(['is_active' => false]);
    }

    /**
     * Check if tenant has valid credentials for a service.
     */
    public function hasValidApiCredential(string $service): bool
    {
        $credential = $this->getApiCredential($service);

        if (!$credential) {
            return false;
        }

        if ($credential->isExpired()) {
            return false;
        }

        return true;
    }
}
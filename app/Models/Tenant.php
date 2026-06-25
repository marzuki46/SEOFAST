<?php

namespace App\Models;

use App\Models\Traits\HasApiCredentials;
use App\Models\Traits\TenantAwareTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    use SoftDeletes, HasApiCredentials;

    protected $fillable = [
        'name',
        'slug',
        'domain',
        'subscription_plan',
        'ai_credit_balance',
        'monthly_url_quota',
        'monthly_url_used',
        'is_active',
        'trial_ends_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'trial_ends_at' => 'datetime',
            'ai_credit_balance' => 'integer',
            'monthly_url_quota' => 'integer',
            'monthly_url_used' => 'integer',
        ];
    }

    public function settings(): HasMany
    {
        return $this->hasMany(TenantSetting::class);
    }


    public function siloBlueprints(): HasMany
    {
        return $this->hasMany(SiloBlueprint::class);
    }

    public function contents(): HasMany
    {
        return $this->hasMany(Content::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function getSetting(string $key, mixed $default = null): mixed
    {
        $setting = $this->settings()->where('key', $key)->first();

        return $setting ? $setting->value : $default;
    }

    public function setSetting(string $key, mixed $value): void
    {
        $this->settings()->updateOrCreate(
            ['key' => $key],
            ['value' => is_array($value) ? json_encode($value) : $value]
        );
    }

    public function hasReachedUrlQuota(): bool
    {
        return $this->monthly_url_used >= $this->monthly_url_quota;
    }

    public function incrementUrlUsage(int $count = 1): void
    {
        $this->increment('monthly_url_used', $count);
    }
}
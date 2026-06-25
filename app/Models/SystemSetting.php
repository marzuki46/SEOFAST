<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SystemSetting extends Model
{
    protected $fillable = ['key', 'value', 'group', 'type', 'label'];

    /**
     * Get a setting value by key with optional default.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $settingData = Cache::remember("system_setting_{$key}", 3600, function () use ($key) {
            $row = static::where('key', $key)->first();
            return $row ? ['type' => $row->type, 'value' => $row->value] : null;
        });

        if (!$settingData) return $default;

        return match($settingData['type']) {
            'boolean' => (bool) $settingData['value'],
            'integer' => (int) $settingData['value'],
            'json'    => json_decode($settingData['value'], true),
            default   => $settingData['value'],
        };
    }

    /**
     * Set a setting value and flush cache.
     */
    public static function set(string $key, mixed $value, string $group = 'general', string $type = 'string', string $label = ''): void
    {
        $storedValue = is_array($value) ? json_encode($value) : (string) $value;

        static::updateOrCreate(
            ['key' => $key],
            ['value' => $storedValue, 'group' => $group, 'type' => $type, 'label' => $label]
        );

        Cache::forget("system_setting_{$key}");
        Cache::forget("system_settings_group_{$group}");
    }

    /**
     * Get all settings in a group.
     */
    public static function group(string $group): array
    {
        return Cache::remember("system_settings_group_{$group}", 3600, function () use ($group) {
            return static::where('group', $group)->pluck('value', 'key')->toArray();
        });
    }

    /**
     * Flush all settings cache.
     */
    public static function flushCache(): void
    {
        Cache::flush();
    }
}

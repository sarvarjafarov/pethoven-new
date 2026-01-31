<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['group', 'key', 'value'];

    /**
     * Get a setting value by "group.key" notation.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $settings = Cache::remember('app_settings', 3600, function () {
            return static::all()->mapWithKeys(function ($setting) {
                return [$setting->group . '.' . $setting->key => $setting->value];
            })->toArray();
        });

        $value = $settings[$key] ?? $default;

        // Try to decode JSON values
        if (is_string($value) && str_starts_with($value, '[') || (is_string($value) && str_starts_with($value, '{'))) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            }
        }

        return $value;
    }

    /**
     * Set a setting value.
     */
    public static function set(string $key, mixed $value): void
    {
        $parts = explode('.', $key, 2);
        $group = $parts[0];
        $settingKey = $parts[1] ?? $parts[0];

        if (is_array($value) || is_object($value)) {
            $value = json_encode($value);
        }

        static::updateOrCreate(
            ['group' => $group, 'key' => $settingKey],
            ['value' => $value]
        );

        Cache::forget('app_settings');
    }

    /**
     * Clear the settings cache.
     */
    public static function clearCache(): void
    {
        Cache::forget('app_settings');
    }
}

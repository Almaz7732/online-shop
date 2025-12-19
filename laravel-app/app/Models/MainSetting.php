<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class MainSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'label',
        'description',
        'order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Get setting value by key
     */
    public static function getValue($key, $default = null)
    {
        $setting = static::where('key', $key)->where('is_active', true)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set setting value by key
     */
    public static function setValue($key, $value, $type = 'text', $label = null, $description = null)
    {
        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'label' => $label,
                'description' => $description,
                'is_active' => true
            ]
        );
    }

    /**
     * Get all settings as key-value pairs
     */
    public static function getAllSettings()
    {
        return static::where('is_active', true)
            ->orderBy('order')
            ->pluck('value', 'key')
            ->toArray();
    }

    /**
     * Get settings by type
     */
    public static function getByType($type)
    {
        return static::where('type', $type)
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
    }

    /**
     * Boot the model and add model events for automatic cache clearing
     */
    protected static function boot()
    {
        parent::boot();

        // Clear cache when a setting is created
        static::created(function ($setting) {
            static::clearSettingsCache($setting);
        });

        // Clear cache when a setting is updated
        static::updated(function ($setting) {
            static::clearSettingsCache($setting);
        });

        // Clear cache when a setting is deleted
        static::deleted(function ($setting) {
            static::clearSettingsCache($setting);
        });
    }

    /**
     * Clear settings cache for a specific setting
     *
     * @param MainSetting $setting
     * @return void
     */
    protected static function clearSettingsCache($setting)
    {
        // Clear specific setting cache
        Cache::forget("setting_{$setting->key}");

        // Clear general settings caches
        Cache::forget('all_settings');

        // Clear carousel cache if it's a carousel-related setting
        if (str_contains($setting->key, 'carousel') || str_contains($setting->key, 'slide')) {
            Cache::forget('active_carousel_slides');
        }

        // Log cache clearing for debugging
        \Log::info("Settings cache cleared for key: {$setting->key}");
    }

    /**
     * Clear all settings cache (public method)
     *
     * @return void
     */
    public static function clearAllCache()
    {
        $settings = static::all();
        foreach ($settings as $setting) {
            Cache::forget("setting_{$setting->key}");
        }
        Cache::forget('all_settings');
        Cache::forget('active_carousel_slides');

        \Log::info('All settings cache cleared manually');
    }
}

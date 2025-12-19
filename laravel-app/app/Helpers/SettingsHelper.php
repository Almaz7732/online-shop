<?php

namespace App\Helpers;

use App\Models\MainSetting;
use App\Models\CarouselSlide;
use Illuminate\Support\Facades\Cache;

class SettingsHelper
{
    /**
     * Cache duration in minutes
     */
    const CACHE_DURATION = 60;

    /**
     * Get a setting value by key with caching
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        return Cache::remember("setting_{$key}", self::CACHE_DURATION, function() use ($key, $default) {
            return MainSetting::getValue($key, $default);
        });
    }

    /**
     * Set a setting value and clear cache
     *
     * @param string $key
     * @param mixed $value
     * @param string $type
     * @param string|null $label
     * @param string|null $description
     * @return \App\Models\MainSetting
     */
    public static function set($key, $value, $type = 'text', $label = null, $description = null)
    {
        $setting = MainSetting::setValue($key, $value, $type, $label, $description);
        Cache::forget("setting_{$key}");
        Cache::forget('all_settings');
        return $setting;
    }

    /**
     * Get all settings as array with caching
     *
     * @return array
     */
    public static function all()
    {
        return Cache::remember('all_settings', self::CACHE_DURATION, function() {
            return MainSetting::getAllSettings();
        });
    }

    /**
     * Get site contact information
     *
     * @return array
     */
    public static function contact()
    {
        return [
            'phone' => self::get('site_phone'),
            'email' => self::get('site_email'),
            'address' => self::get('site_address'),
        ];
    }

    /**
     * Get social media links
     *
     * @return array
     */
    public static function social()
    {
        return [
            'instagram' => self::get('site_instagram'),
            'facebook' => self::get('site_facebook'),
            'twitter' => self::get('site_twitter'),
            'youtube' => self::get('site_youtube'),
        ];
    }

    /**
     * Get active carousel slides with caching
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function carousel()
    {
        return Cache::remember('active_carousel_slides', self::CACHE_DURATION, function() {
            return CarouselSlide::active()->ordered()->get();
        });
    }

    /**
     * Clear all settings cache
     *
     * @return void
     */
    public static function clearCache()
    {
        $settings = MainSetting::all();
        foreach ($settings as $setting) {
            Cache::forget("setting_{$setting->key}");
        }
        Cache::forget('all_settings');
        Cache::forget('active_carousel_slides');

        \Log::info('All settings cache cleared via SettingsHelper');
    }

    /**
     * Clear cache for a specific setting
     *
     * @param string $key
     * @return void
     */
    public static function clearCacheForKey($key)
    {
        Cache::forget("setting_{$key}");
        Cache::forget('all_settings');

        \Log::info("Settings cache cleared for key: {$key}");
    }

    /**
     * Clear only carousel cache
     *
     * @return void
     */
    public static function clearCarouselCache()
    {
        Cache::forget('active_carousel_slides');

        \Log::info('Carousel cache cleared');
    }

    /**
     * Refresh cache for a specific setting
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function refresh($key, $default = null)
    {
        Cache::forget("setting_{$key}");
        return static::get($key, $default);
    }

    /**
     * Get cache statistics
     *
     * @return array
     */
    public static function getCacheStats()
    {
        $settings = MainSetting::all();
        $cached = 0;
        $total = $settings->count();

        foreach ($settings as $setting) {
            if (Cache::has("setting_{$setting->key}")) {
                $cached++;
            }
        }

        return [
            'total_settings' => $total,
            'cached_settings' => $cached,
            'cache_hit_rate' => $total > 0 ? round(($cached / $total) * 100, 2) : 0,
            'all_settings_cached' => Cache::has('all_settings'),
            'carousel_cached' => Cache::has('active_carousel_slides'),
        ];
    }

    /**
     * Check if a feature is enabled
     *
     * @param string $feature
     * @return bool
     */
    public static function isEnabled($feature)
    {
        return (bool) self::get("feature_{$feature}", false);
    }

    /**
     * Get theme settings
     *
     * @return array
     */
    public static function theme()
    {
        return [
            'primary_color' => self::get('theme_primary_color', '#007bff'),
            'secondary_color' => self::get('theme_secondary_color', '#6c757d'),
            'logo' => self::get('site_logo'),
            'favicon' => self::get('site_favicon'),
        ];
    }

    /**
     * Get SEO settings
     *
     * @return array
     */
    public static function seo()
    {
        return [
            'title' => self::get('seo_title'),
            'description' => self::get('seo_description'),
            'keywords' => self::get('seo_keywords'),
            'image' => self::get('seo_image'),
        ];
    }
}
<?php

use App\Helpers\SettingsHelper;

if (!function_exists('setting')) {
    /**
     * Get a setting value by key
     *
     * @param string|null $key
     * @param mixed $default
     * @return mixed|\App\Helpers\SettingsHelper
     */
    function setting($key = null, $default = null)
    {
        if ($key === null) {
            return app(SettingsHelper::class);
        }

        return SettingsHelper::get($key, $default);
    }
}

if (!function_exists('carousel_slides')) {
    /**
     * Get active carousel slides
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    function carousel_slides()
    {
        return SettingsHelper::carousel();
    }
}

if (!function_exists('site_contact')) {
    /**
     * Get site contact information
     *
     * @return array
     */
    function site_contact()
    {
        return SettingsHelper::contact();
    }
}

if (!function_exists('site_social')) {
    /**
     * Get social media links
     *
     * @return array
     */
    function site_social()
    {
        return SettingsHelper::social();
    }
}

if (!function_exists('site_theme')) {
    /**
     * Get theme settings
     *
     * @return array
     */
    function site_theme()
    {
        return SettingsHelper::theme();
    }
}
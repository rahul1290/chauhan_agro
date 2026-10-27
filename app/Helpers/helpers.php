<?php

use App\Helpers\LocalizationHelper;
use App\Models\Language;
use App\Models\Setting;

if (!function_exists('loc_url')) {
    function loc_url(string $path = '', ?string $locale = null): string
    {
        return LocalizationHelper::url($path, $locale);
    }
}

if (!function_exists('loc_switch')) {
    function loc_switch(string $targetLocale): string
    {
        return LocalizationHelper::switchUrl($targetLocale);
    }
}

if (!function_exists('site_setting')) {
    function site_setting(string $key, $default = null)
    {
        return Setting::get($key, $default);
    }
}

if (!function_exists('active_languages')) {
    function active_languages()
    {
        return Language::active()->get();
    }
}

if (!function_exists('current_locale')) {
    function current_locale(): string
    {
        return LocalizationHelper::currentLocale();
    }
}

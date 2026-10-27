<?php

namespace App\Traits;

use App\Models\Language;

trait HasTranslationsTrait
{
    /**
     * Get translation for specified locale or current active locale, with fallback.
     */
    public function translation(?string $locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        $fallbackLocale = config('app.fallback_locale', 'en');

        // Check if translations relation is loaded or query it
        $translations = $this->relationLoaded('translations')
            ? $this->translations
            : $this->translations()->get();

        $translation = $translations->firstWhere('locale', $locale);

        if (!$translation && $locale !== $fallbackLocale) {
            $translation = $translations->firstWhere('locale', $fallbackLocale);
        }

        if (!$translation && $translations->isNotEmpty()) {
            $translation = $translations->first();
        }

        return $translation;
    }

    /**
     * Check if translation exists for a specific locale.
     */
    public function hasTranslation(string $locale): bool
    {
        $translations = $this->relationLoaded('translations')
            ? $this->translations
            : $this->translations()->get();

        return $translations->contains('locale', $locale);
    }
}

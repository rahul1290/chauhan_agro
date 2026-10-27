<?php

namespace App\Helpers;

use App\Models\Language;
use App\Models\Page;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Request;

class LocalizationHelper
{
    /**
     * Get the current active locale code.
     */
    public static function currentLocale(): string
    {
        return app()->getLocale();
    }

    /**
     * Generate localized URL for a path or named route.
     */
    public static function url(string $path = '', ?string $locale = null): string
    {
        $locale = $locale ?: static::currentLocale();
        $trimmed = ltrim($path, '/');

        return url("/{$locale}/" . $trimmed);
    }

    /**
     * Build the URL for switching language, preserving the current page/product.
     */
    public static function switchUrl(string $targetLocale): string
    {
        $request = request();
        $currentLocale = static::currentLocale();

        // If on home page
        if ($request->path() === '/' || $request->path() === $currentLocale) {
            return url("/{$targetLocale}");
        }

        // Check if viewing product detail: /{locale}/products/{slug}
        if ($request->is("*/products/*")) {
            $slug = $request->segment(3);
            if ($slug) {
                // Find product by slug in current locale or any locale
                $product = Product::where('slug', $slug)
                    ->orWhereHas('translations', function ($q) use ($slug) {
                        $q->where('slug', $slug);
                    })->first();

                if ($product) {
                    $targetTrans = $product->translations()->where('locale', $targetLocale)->first();
                    $targetSlug = $targetTrans && !empty($targetTrans->slug) ? $targetTrans->slug : $product->slug;
                    return url("/{$targetLocale}/products/{$targetSlug}");
                }
            }
        }

        // Check if viewing products list: /{locale}/products
        if ($request->is("*/products")) {
            $query = $request->getQueryString();
            return url("/{$targetLocale}/products" . ($query ? "?{$query}" : ''));
        }

        // Check if viewing CMS page: /{locale}/{slug}
        $pageSlug = $request->segment(2);
        if ($pageSlug) {
            $page = Page::where('slug', $pageSlug)
                ->orWhereHas('translations', function ($q) use ($pageSlug) {
                    $q->where('slug', $pageSlug);
                })->first();

            if ($page) {
                $targetTrans = $page->translations()->where('locale', $targetLocale)->first();
                $targetSlug = $targetTrans && !empty($targetTrans->slug) ? $targetTrans->slug : $page->slug;
                return url("/{$targetLocale}/{$targetSlug}");
            }
        }

        // Generic fallback: replace first segment with target locale
        $segments = $request->segments();
        if (!empty($segments) && in_array($segments[0], [Language::pluck('code')->toArray()])) {
            $segments[0] = $targetLocale;
            return url(implode('/', $segments));
        }

        return url("/{$targetLocale}/" . implode('/', $segments));
    }
}

<?php

namespace App\Http\Middleware;

use App\Models\Language;
use App\Models\Menu;
use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Don't modify locale for admin routes or storage routes
        if ($request->is('admin*') || $request->is('api*') || $request->is('storage*')) {
            return $next($request);
        }

        $segment = $request->segment(1);
        $activeLanguages = [];

        try {
            if (Schema::hasTable('languages')) {
                $activeLanguages = Language::active()->get();
            }
        } catch (\Throwable $e) {
            // Fallback during initial boot
        }

        $supportedCodes = !empty($activeLanguages) ? $activeLanguages->pluck('code')->toArray() : ['en', 'hi'];
        $defaultLang = !empty($activeLanguages) ? $activeLanguages->firstWhere('is_default', true) : null;
        $defaultCode = $defaultLang ? $defaultLang->code : 'en';

        if ($segment && in_array($segment, $supportedCodes)) {
            $locale = $segment;
            Session::put('locale', $locale);
        } else {
            // Check session or default
            $locale = Session::get('locale', $defaultCode);
            if (!in_array($locale, $supportedCodes)) {
                $locale = $defaultCode;
            }
        }

        App::setLocale($locale);

        // Share common view variables
        if (!empty($activeLanguages)) {
            View::share('activeLanguages', $activeLanguages);
            View::share('currentLocale', $locale);
            View::share('currentLanguage', $activeLanguages->firstWhere('code', $locale) ?? $defaultLang);

            // Share site menus
            try {
                if (Schema::hasTable('menus')) {
                    $headerMenu = Menu::where('location', 'header')->where('is_active', true)->with(['items.children.translations', 'items.translations'])->first();
                    $footerQuickMenu = Menu::where('location', 'footer_quick')->where('is_active', true)->with('items.translations')->first();
                    $footerProductsMenu = Menu::where('location', 'footer_products')->where('is_active', true)->with('items.translations')->first();
                    $footerLegalMenu = Menu::where('location', 'footer_legal')->where('is_active', true)->with('items.translations')->first();

                    View::share('headerMenu', $headerMenu);
                    View::share('footerQuickMenu', $footerQuickMenu);
                    View::share('footerProductsMenu', $footerProductsMenu);
                    View::share('footerLegalMenu', $footerLegalMenu);
                }
            } catch (\Throwable $e) {
                // Ignore menu loading errors if schema is updating
            }
        }

        return $next($request);
    }
}

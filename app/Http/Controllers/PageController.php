<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show($locale, $slug = null)
    {
        if ($slug === null) {
            $slug = $locale;
        }
        $page = Page::where('is_published', true)
            ->where(function ($q) use ($slug) {
                $q->where('slug', $slug)
                  ->orWhereHas('translations', function ($tq) use ($slug) {
                      $tq->where('slug', $slug);
                  });
            })
            ->with(['translations', 'activeSections.translations'])
            ->firstOrFail();

        // Redirect 'home' to root localized home
        if ($page->slug === 'home') {
            return redirect()->route('home', ['locale' => app()->getLocale()]);
        }

        if ($page->template === 'contact') {
            return redirect()->route('contact.show', ['locale' => app()->getLocale()]);
        }

        $banners = Banner::where('is_active', true)
            ->where(function ($q) use ($page) {
                $q->where('page_slug', $page->slug)
                  ->orWhere('page_slug', 'home');
            })
            ->with('translations')
            ->orderBy('order', 'asc')
            ->get();

        return view('frontend.pages.show', compact('page', 'banners'));
    }
}

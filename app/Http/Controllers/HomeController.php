<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Page;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::where('page_slug', 'home')
            ->where('is_active', true)
            ->with('translations')
            ->orderBy('order', 'asc')
            ->get();

        $page = Page::where('slug', 'home')
            ->with(['translations', 'activeSections.translations'])
            ->first();

        $featuredCategories = ProductCategory::where('is_active', true)
            ->where('is_featured', true)
            ->with('translations')
            ->orderBy('order', 'asc')
            ->take(6)
            ->get();

        $featuredProducts = Product::where('is_active', true)
            ->where('is_featured', true)
            ->with(['category.translations', 'translations', 'specifications.translations'])
            ->orderBy('order', 'asc')
            ->take(6)
            ->get();

        return view('frontend.home', compact('banners', 'page', 'featuredCategories', 'featuredProducts'));
    }
}

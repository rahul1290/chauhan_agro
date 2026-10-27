<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('is_active', true)
            ->with(['category.translations', 'translations', 'specifications.translations']);

        $selectedCategory = null;
        if ($request->filled('category')) {
            $catSlug = $request->category;
            $selectedCategory = ProductCategory::where('slug', $catSlug)
                ->orWhereHas('translations', function ($q) use ($catSlug) {
                    $q->where('slug', $catSlug);
                })->first();

            if ($selectedCategory) {
                $query->where('category_id', $selectedCategory->id);
            }
        }

        if ($request->filled('hp')) {
            $hp = (int)$request->hp;
            $query->where(function ($q) use ($hp) {
                $q->where('tractor_hp_min', '<=', $hp)
                  ->where('tractor_hp_max', '>=', $hp);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('sku', 'like', "%{$search}%")
                  ->orWhereHas('translations', function ($tq) use ($search) {
                      $tq->where('name', 'like', "%{$search}%")
                         ->orWhere('short_description', 'like', "%{$search}%");
                  });
            });
        }

        $sort = $request->get('sort', 'featured');
        if ($sort === 'hp_asc') {
            $query->orderBy('tractor_hp_min', 'asc');
        } elseif ($sort === 'hp_desc') {
            $query->orderBy('tractor_hp_max', 'desc');
        } else {
            $query->orderBy('is_featured', 'desc')->orderBy('order', 'asc');
        }

        $products = $query->paginate(9)->withQueryString();
        $categories = ProductCategory::where('is_active', true)->with('translations')->withCount('products')->get();

        return view('frontend.products.index', compact('products', 'categories', 'selectedCategory'));
    }

    public function show($locale, $slug = null)
    {
        if ($slug === null) {
            $slug = $locale;
        }
        $product = Product::where('slug', $slug)
            ->orWhereHas('translations', function ($q) use ($slug) {
                $q->where('slug', $slug);
            })
            ->with(['category.translations', 'translations', 'images', 'specifications.translations', 'relatedProducts.translations'])
            ->firstOrFail();

        $relatedProducts = $product->relatedProducts()
            ->where('is_active', true)
            ->with(['category.translations', 'translations'])
            ->take(3)
            ->get();

        if ($relatedProducts->isEmpty()) {
            $relatedProducts = Product::where('category_id', $product->category_id)
                ->where('id', '!=', $product->id)
                ->where('is_active', true)
                ->with(['category.translations', 'translations'])
                ->take(3)
                ->get();
        }

        return view('frontend.products.show', compact('product', 'relatedProducts'));
    }
}

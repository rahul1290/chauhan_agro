<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Language;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Models\ProductSpecification;
use App\Models\ProductSpecificationTranslation;
use App\Models\ProductTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'translations']);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        if ($request->filled('featured')) {
            $query->where('is_featured', true);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('slug', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhereHas('translations', function ($tq) use ($search) {
                      $tq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $products = $query->orderBy('order', 'asc')->paginate(15);
        $categories = ProductCategory::all();
        $languages = Language::active()->get();

        return view('admin.products.index', compact('products', 'categories', 'languages'));
    }

    public function create()
    {
        $categories = ProductCategory::where('is_active', true)->get();
        $languages = Language::active()->get();
        $allProducts = Product::where('is_active', true)->get();

        return view('admin.products.create', compact('categories', 'languages', 'allProducts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => ['required', 'exists:product_categories,id'],
            'sku' => ['nullable', 'string', 'max:50'],
            'slug' => ['required', 'string', 'max:100', 'unique:products,slug'],
            'featured_image' => ['nullable', 'image', 'max:5120'],
            'brochure_pdf' => ['nullable', 'mimes:pdf', 'max:10240'],
            'tractor_hp_min' => ['nullable', 'integer'],
            'tractor_hp_max' => ['nullable', 'integer'],
            'working_width' => ['nullable', 'string', 'max:100'],
            'weight_approx' => ['nullable', 'string', 'max:100'],
            'blades_count' => ['nullable', 'integer'],
            'translations' => ['required', 'array'],
            'translations.*.name' => ['required', 'string', 'max:255'],
        ]);

        $featuredImagePath = null;
        if ($request->hasFile('featured_image')) {
            $featuredImagePath = '/storage/' . $request->file('featured_image')->store('products', 'public');
        }

        $brochurePath = null;
        if ($request->hasFile('brochure_pdf')) {
            $brochurePath = '/storage/' . $request->file('brochure_pdf')->store('brochures', 'public');
        }

        $product = Product::create([
            'category_id' => $request->category_id,
            'sku' => $request->sku,
            'slug' => Str::slug($request->slug),
            'featured_image' => $featuredImagePath,
            'brochure_pdf' => $brochurePath,
            'tractor_hp_min' => $request->tractor_hp_min,
            'tractor_hp_max' => $request->tractor_hp_max,
            'working_width' => $request->working_width,
            'weight_approx' => $request->weight_approx,
            'blades_count' => $request->blades_count,
            'is_featured' => $request->boolean('is_featured', false),
            'is_active' => $request->boolean('is_active', true),
            'order' => Product::max('order') + 1,
        ]);

        // Translations
        foreach ($request->translations as $locale => $tData) {
            if (!empty($tData['name'])) {
                $features = !empty($tData['features']) ? array_filter(array_map('trim', explode("\n", $tData['features']))) : [];

                ProductTranslation::create([
                    'product_id' => $product->id,
                    'locale' => $locale,
                    'name' => $tData['name'],
                    'slug' => !empty($tData['slug']) ? Str::slug($tData['slug']) : Str::slug($tData['name']),
                    'short_description' => $tData['short_description'] ?? null,
                    'description' => $tData['description'] ?? null,
                    'features_json' => $features,
                    'meta_title' => $tData['meta_title'] ?? $tData['name'],
                    'meta_description' => $tData['meta_description'] ?? null,
                ]);
            }
        }

        // Specifications
        if ($request->filled('specs') && is_array($request->specs)) {
            $specOrder = 1;
            foreach ($request->specs as $specRow) {
                if (!empty($specRow['en']['key'])) {
                    $spec = ProductSpecification::create([
                        'product_id' => $product->id,
                        'order' => $specOrder++,
                    ]);

                    foreach (['en', 'hi'] as $langCode) {
                        if (!empty($specRow[$langCode]['key'])) {
                            ProductSpecificationTranslation::create([
                                'specification_id' => $spec->id,
                                'locale' => $langCode,
                                'spec_key' => $specRow[$langCode]['key'],
                                'spec_value' => $specRow[$langCode]['val'] ?? '',
                            ]);
                        }
                    }
                }
            }
        }

        // Related Products
        if ($request->filled('related_ids')) {
            $product->relatedProducts()->sync($request->related_ids);
        }

        ActivityLog::log('create_product', "Created product: {$product->slug}");

        return redirect()->route('admin.products.index')->with('success', 'Machinery product added successfully.');
    }

    public function edit(Product $product)
    {
        $categories = ProductCategory::where('is_active', true)->get();
        $languages = Language::active()->get();
        $allProducts = Product::where('id', '!=', $product->id)->where('is_active', true)->get();
        $translations = $product->translations->keyBy('locale');
        $specs = $product->specifications()->with('translations')->get();

        return view('admin.products.edit', compact('product', 'categories', 'languages', 'allProducts', 'translations', 'specs'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => ['required', 'exists:product_categories,id'],
            'sku' => ['nullable', 'string', 'max:50'],
            'slug' => ['required', 'string', 'max:100', 'unique:products,slug,' . $product->id],
            'featured_image' => ['nullable', 'image', 'max:5120'],
            'brochure_pdf' => ['nullable', 'mimes:pdf', 'max:10240'],
            'tractor_hp_min' => ['nullable', 'integer'],
            'tractor_hp_max' => ['nullable', 'integer'],
            'working_width' => ['nullable', 'string', 'max:100'],
            'weight_approx' => ['nullable', 'string', 'max:100'],
            'blades_count' => ['nullable', 'integer'],
            'translations' => ['required', 'array'],
        ]);

        if ($request->hasFile('featured_image')) {
            $product->featured_image = '/storage/' . $request->file('featured_image')->store('products', 'public');
        }

        if ($request->hasFile('brochure_pdf')) {
            $product->brochure_pdf = '/storage/' . $request->file('brochure_pdf')->store('brochures', 'public');
        }

        $product->category_id = $request->category_id;
        $product->sku = $request->sku;
        $product->slug = Str::slug($request->slug);
        $product->tractor_hp_min = $request->tractor_hp_min;
        $product->tractor_hp_max = $request->tractor_hp_max;
        $product->working_width = $request->working_width;
        $product->weight_approx = $request->weight_approx;
        $product->blades_count = $request->blades_count;
        $product->is_featured = $request->boolean('is_featured', false);
        $product->is_active = $request->boolean('is_active', true);
        $product->save();

        foreach ($request->translations as $locale => $tData) {
            if (!empty($tData['name'])) {
                $features = !empty($tData['features']) ? array_filter(array_map('trim', explode("\n", $tData['features']))) : [];

                ProductTranslation::updateOrCreate(
                    ['product_id' => $product->id, 'locale' => $locale],
                    [
                        'name' => $tData['name'],
                        'slug' => !empty($tData['slug']) ? Str::slug($tData['slug']) : Str::slug($tData['name']),
                        'short_description' => $tData['short_description'] ?? null,
                        'description' => $tData['description'] ?? null,
                        'features_json' => $features,
                        'meta_title' => $tData['meta_title'] ?? $tData['name'],
                        'meta_description' => $tData['meta_description'] ?? null,
                    ]
                );
            }
        }

        // Update specifications if provided
        if ($request->has('specs') && is_array($request->specs)) {
            $product->specifications()->delete();
            $specOrder = 1;
            foreach ($request->specs as $specRow) {
                if (!empty($specRow['en']['key'])) {
                    $spec = ProductSpecification::create([
                        'product_id' => $product->id,
                        'order' => $specOrder++,
                    ]);

                    foreach (['en', 'hi'] as $langCode) {
                        if (!empty($specRow[$langCode]['key'])) {
                            ProductSpecificationTranslation::create([
                                'specification_id' => $spec->id,
                                'locale' => $langCode,
                                'spec_key' => $specRow[$langCode]['key'],
                                'spec_value' => $specRow[$langCode]['val'] ?? '',
                            ]);
                        }
                    }
                }
            }
        }

        // Related Products
        $product->relatedProducts()->sync($request->related_ids ?? []);

        ActivityLog::log('update_product', "Updated product: {$product->slug}");

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $slug = $product->slug;
        $product->delete();

        ActivityLog::log('delete_product', "Deleted product: {$slug}");

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}

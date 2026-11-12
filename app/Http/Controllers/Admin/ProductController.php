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
        $query = Product::with(['category.translations', 'translations']);

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

        // Sorting Parameters
        $sort = $request->get('sort', 'order');
        $direction = strtolower($request->get('direction', 'asc')) === 'desc' ? 'desc' : 'asc';

        // Support compound sort parameters from dropdown e.g. hp_asc, name_desc
        if (str_contains($sort, '_') && !in_array($sort, ['tractor_hp_min', 'tractor_hp_max', 'created_at', 'is_active', 'is_featured'])) {
            $parts = explode('_', $sort);
            $lastPart = end($parts);
            if (in_array($lastPart, ['asc', 'desc'])) {
                $direction = $lastPart;
                $sort = implode('_', array_slice($parts, 0, -1));
            }
        }

        $currentLocale = app()->getLocale();

        switch ($sort) {
            case 'name':
                $query->leftJoin('product_translations', function ($join) use ($currentLocale) {
                    $join->on('products.id', '=', 'product_translations.product_id')
                         ->where('product_translations.locale', '=', $currentLocale);
                })
                ->select('products.*')
                ->orderBy('product_translations.name', $direction);
                break;

            case 'category':
                $query->leftJoin('product_categories', 'products.category_id', '=', 'product_categories.id')
                      ->leftJoin('product_category_translations', function ($join) use ($currentLocale) {
                          $join->on('product_categories.id', '=', 'product_category_translations.category_id')
                               ->where('product_category_translations.locale', '=', $currentLocale);
                      })
                      ->select('products.*')
                      ->orderBy('product_category_translations.name', $direction);
                break;

            case 'hp':
            case 'tractor_hp_min':
                $query->orderBy('tractor_hp_min', $direction);
                break;

            case 'sku':
                $query->orderBy('sku', $direction);
                break;

            case 'status':
            case 'is_active':
                $query->orderBy('is_active', $direction);
                break;

            case 'featured':
            case 'is_featured':
                $query->orderBy('is_featured', $direction);
                break;

            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;

            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;

            case 'created_at':
                $query->orderBy('created_at', $direction);
                break;

            case 'order':
            default:
                $sort = 'order';
                $query->orderBy('order', $direction)->orderBy('id', 'asc');
                break;
        }

        $products = $query->paginate(15)->withQueryString();
        $categories = ProductCategory::with('translations')->orderBy('order', 'asc')->get();
        $languages = Language::active()->get();

        return view('admin.products.index', compact('products', 'categories', 'languages', 'sort', 'direction'));
    }

    public function create()
    {
        $categories = ProductCategory::where('is_active', true)->with('translations')->orderBy('order', 'asc')->get();
        $languages = Language::active()->get();
        $allProducts = Product::where('is_active', true)->with('translations')->get();
        $nextOrder = (Product::max('order') ?? 0) + 1;

        return view('admin.products.create', compact('categories', 'languages', 'allProducts', 'nextOrder'));
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
            'order' => ['nullable', 'integer', 'min:0'],
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

        $order = $request->filled('order') ? (int)$request->order : ((Product::max('order') ?? 0) + 1);

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
            'order' => $order,
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
        $categories = ProductCategory::where('is_active', true)->with('translations')->orderBy('order', 'asc')->get();
        $languages = Language::active()->get();
        $allProducts = Product::where('id', '!=', $product->id)->where('is_active', true)->with('translations')->get();
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
            'order' => ['nullable', 'integer', 'min:0'],
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
        if ($request->filled('order')) {
            $product->order = (int)$request->order;
        }
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

    public function reorder(Request $request, Product $product, string $direction)
    {
        $query = Product::query();

        // If category filter was passed, reorder within that category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $all = $query->orderBy('order', 'asc')->orderBy('id', 'asc')->get();
        $index = $all->search(fn($p) => $p->id === $product->id);

        if ($index === false) {
            return back()->with('error', 'Product not found in sequence.');
        }

        $swapIndex = $direction === 'up' ? $index - 1 : $index + 1;

        if ($swapIndex >= 0 && $swapIndex < $all->count()) {
            $target = $all[$swapIndex];

            // If orders are identical or not cleanly spaced, re-sequence properly
            if ($target->order == $product->order) {
                foreach ($all as $i => $item) {
                    $item->order = ($i + 1) * 10;
                }
                $all[$index]->order = ($swapIndex + 1) * 10;
                $all[$swapIndex]->order = ($index + 1) * 10;
                foreach ($all as $item) {
                    $item->save();
                }
            } else {
                $targetOrder = $target->order;
                $target->order = $product->order;
                $product->order = $targetOrder;
                $target->save();
                $product->save();
            }

            ActivityLog::log('reorder_product', "Reordered product: {$product->slug} ({$direction})");

            return back()->with('success', "Machinery model '{$product->name}' order updated.");
        }

        return back()->with('info', "Machinery model is already at the " . ($direction === 'up' ? 'top' : 'bottom') . ".");
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'order' => ['nullable', 'array'],
            'ids' => ['nullable', 'array'],
        ]);

        $ids = $request->input('ids') ?? $request->input('order');

        if (is_array($ids)) {
            foreach ($ids as $index => $id) {
                if (is_array($id) && isset($id['id'])) {
                    $orderVal = isset($id['order']) ? (int)$id['order'] : ($index + 1);
                    Product::where('id', $id['id'])->update(['order' => $orderVal]);
                } else {
                    Product::where('id', $id)->update(['order' => $index + 1]);
                }
            }

            ActivityLog::log('reorder_product', "Updated display sequence for products.");
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Product sequence updated successfully.'
            ]);
        }

        return back()->with('success', 'Product sequence updated successfully.');
    }

    public function destroy(Product $product)
    {
        $slug = $product->slug;
        $product->delete();

        ActivityLog::log('delete_product', "Deleted product: {$slug}");

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}

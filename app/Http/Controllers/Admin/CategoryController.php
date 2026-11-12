<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Language;
use App\Models\ProductCategory;
use App\Models\ProductCategoryTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductCategory::with(['parent', 'translations', 'products'])
            ->withCount('products');

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('slug', 'like', "%{$search}%")
                  ->orWhereHas('translations', function ($tq) use ($search) {
                      $tq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Parent Category Filter
        if ($request->filled('parent_id')) {
            if ($request->parent_id === 'root') {
                $query->whereNull('parent_id');
            } else {
                $query->where('parent_id', $request->parent_id);
            }
        }

        // Status Filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Sorting Parameters
        $sort = $request->get('sort', 'order');
        $direction = strtolower($request->get('direction', 'asc')) === 'desc' ? 'desc' : 'asc';

        // Support compound sort parameters from dropdown e.g. name_asc, order_desc
        if (str_contains($sort, '_') && !in_array($sort, ['created_at', 'is_active', 'is_featured'])) {
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
                $query->leftJoin('product_category_translations', function ($join) use ($currentLocale) {
                    $join->on('product_categories.id', '=', 'product_category_translations.category_id')
                         ->where('product_category_translations.locale', '=', $currentLocale);
                })
                ->select('product_categories.*')
                ->orderBy('product_category_translations.name', $direction);
                break;

            case 'products':
            case 'products_count':
                $query->orderBy('products_count', $direction);
                break;

            case 'status':
            case 'is_active':
                $query->orderBy('is_active', $direction);
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

        $categories = $query->paginate(15)->withQueryString();
        $parentCategories = ProductCategory::whereNull('parent_id')->get();

        return view('admin.categories.index', compact('categories', 'parentCategories', 'sort', 'direction'));
    }

    public function create()
    {
        $languages = Language::active()->get();
        $parentCategories = ProductCategory::whereNull('parent_id')->get();
        $nextOrder = (ProductCategory::max('order') ?? 0) + 1;

        return view('admin.categories.create', compact('languages', 'parentCategories', 'nextOrder'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'slug' => ['required', 'string', 'max:100', 'unique:product_categories,slug'],
            'image' => ['nullable', 'image', 'max:4096'],
            'icon' => ['nullable', 'string', 'max:50'],
            'order' => ['nullable', 'integer', 'min:0'],
            'parent_id' => ['nullable', 'exists:product_categories,id'],
            'translations' => ['required', 'array'],
            'translations.*.name' => ['required', 'string', 'max:255'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = '/storage/' . $request->file('image')->store('categories', 'public');
        }

        $order = $request->filled('order') ? (int)$request->order : ((ProductCategory::max('order') ?? 0) + 1);

        $category = ProductCategory::create([
            'parent_id' => $request->parent_id,
            'slug' => Str::slug($request->slug),
            'image' => $imagePath,
            'icon' => $request->icon ?? 'bi-gear-wide',
            'order' => $order,
            'is_active' => $request->boolean('is_active', true),
            'is_featured' => $request->boolean('is_featured', false),
        ]);

        foreach ($request->translations as $locale => $tData) {
            if (!empty($tData['name'])) {
                ProductCategoryTranslation::create([
                    'category_id' => $category->id,
                    'locale' => $locale,
                    'name' => $tData['name'],
                    'slug' => !empty($tData['slug']) ? Str::slug($tData['slug']) : Str::slug($tData['name']),
                    'description' => $tData['description'] ?? null,
                    'meta_title' => $tData['meta_title'] ?? $tData['name'],
                    'meta_description' => $tData['meta_description'] ?? null,
                ]);
            }
        }

        ActivityLog::log('create_category', "Created category: {$category->slug}");

        return redirect()->route('admin.categories.index')->with('success', 'Product category created successfully.');
    }

    public function edit(ProductCategory $category)
    {
        $languages = Language::active()->get();
        $parentCategories = ProductCategory::whereNull('parent_id')->where('id', '!=', $category->id)->get();
        $translations = $category->translations->keyBy('locale');

        return view('admin.categories.edit', compact('category', 'languages', 'parentCategories', 'translations'));
    }

    public function update(Request $request, ProductCategory $category)
    {
        $request->validate([
            'slug' => ['required', 'string', 'max:100', 'unique:product_categories,slug,' . $category->id],
            'image' => ['nullable', 'image', 'max:4096'],
            'icon' => ['nullable', 'string', 'max:50'],
            'order' => ['nullable', 'integer', 'min:0'],
            'parent_id' => ['nullable', 'exists:product_categories,id'],
            'translations' => ['required', 'array'],
        ]);

        if ($request->hasFile('image')) {
            $category->image = '/storage/' . $request->file('image')->store('categories', 'public');
        }

        $category->parent_id = $request->parent_id;
        $category->slug = Str::slug($request->slug);
        $category->icon = $request->icon ?? $category->icon;
        if ($request->filled('order')) {
            $category->order = (int)$request->order;
        }
        $category->is_active = $request->boolean('is_active', true);
        $category->is_featured = $request->boolean('is_featured', false);
        $category->save();

        foreach ($request->translations as $locale => $tData) {
            if (!empty($tData['name'])) {
                ProductCategoryTranslation::updateOrCreate(
                    ['category_id' => $category->id, 'locale' => $locale],
                    [
                        'name' => $tData['name'],
                        'slug' => !empty($tData['slug']) ? Str::slug($tData['slug']) : Str::slug($tData['name']),
                        'description' => $tData['description'] ?? null,
                        'meta_title' => $tData['meta_title'] ?? $tData['name'],
                        'meta_description' => $tData['meta_description'] ?? null,
                    ]
                );
            }
        }

        ActivityLog::log('update_category', "Updated category: {$category->slug}");

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function reorder(Request $request, ProductCategory $category, string $direction)
    {
        $all = ProductCategory::orderBy('order', 'asc')->orderBy('id', 'asc')->get();
        $index = $all->search(fn($c) => $c->id === $category->id);

        if ($index === false) {
            return back()->with('error', 'Category not found in sequence.');
        }

        $swapIndex = $direction === 'up' ? $index - 1 : $index + 1;

        if ($swapIndex >= 0 && $swapIndex < $all->count()) {
            $target = $all[$swapIndex];

            // If orders are identical or not cleanly spaced, re-sequence properly
            if ($target->order == $category->order) {
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
                $target->order = $category->order;
                $category->order = $targetOrder;
                $target->save();
                $category->save();
            }

            ActivityLog::log('reorder_category', "Reordered category: {$category->slug} ({$direction})");

            return back()->with('success', "Category '{$category->name}' order updated.");
        }

        return back()->with('info', "Category is already at the " . ($direction === 'up' ? 'top' : 'bottom') . ".");
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
                    ProductCategory::where('id', $id['id'])->update(['order' => $orderVal]);
                } else {
                    ProductCategory::where('id', $id)->update(['order' => $index + 1]);
                }
            }

            ActivityLog::log('reorder_category', "Updated display sequence for categories.");
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Category sequence updated successfully.'
            ]);
        }

        return back()->with('success', 'Category sequence updated successfully.');
    }

    public function destroy(ProductCategory $category)
    {
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Cannot delete category that contains products. Reassign products first.');
        }

        if ($category->children()->count() > 0) {
            return back()->with('error', 'Cannot delete category that has subcategories. Remove subcategories first.');
        }

        $slug = $category->slug;
        $category->delete();

        ActivityLog::log('delete_category', "Deleted category: {$slug}");

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }
}

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
    public function index()
    {
        $categories = ProductCategory::with(['parent', 'translations', 'products'])
            ->orderBy('order', 'asc')
            ->paginate(15);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $languages = Language::active()->get();
        $parentCategories = ProductCategory::whereNull('parent_id')->get();

        return view('admin.categories.create', compact('languages', 'parentCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'slug' => ['required', 'string', 'max:100', 'unique:product_categories,slug'],
            'image' => ['nullable', 'image', 'max:4096'],
            'icon' => ['nullable', 'string', 'max:50'],
            'parent_id' => ['nullable', 'exists:product_categories,id'],
            'translations' => ['required', 'array'],
            'translations.*.name' => ['required', 'string', 'max:255'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = '/storage/' . $request->file('image')->store('categories', 'public');
        }

        $category = ProductCategory::create([
            'parent_id' => $request->parent_id,
            'slug' => Str::slug($request->slug),
            'image' => $imagePath,
            'icon' => $request->icon ?? 'bi-gear-wide',
            'order' => ProductCategory::max('order') + 1,
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
            'parent_id' => ['nullable', 'exists:product_categories,id'],
            'translations' => ['required', 'array'],
        ]);

        if ($request->hasFile('image')) {
            $category->image = '/storage/' . $request->file('image')->store('categories', 'public');
        }

        $category->parent_id = $request->parent_id;
        $category->slug = Str::slug($request->slug);
        $category->icon = $request->icon ?? $category->icon;
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

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Language;
use App\Models\Page;
use App\Models\PageTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $query = Page::with('translations');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('slug', 'like', "%{$search}%")
                  ->orWhereHas('translations', function ($tq) use ($search) {
                      $tq->where('title', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('is_published', $request->status === 'published');
        }

        $pages = $query->orderBy('order', 'asc')->paginate(15);
        $languages = Language::active()->get();

        return view('admin.pages.index', compact('pages', 'languages'));
    }

    public function create()
    {
        $languages = Language::active()->get();
        return view('admin.pages.create', compact('languages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'slug' => ['required', 'string', 'max:100', 'unique:pages,slug'],
            'template' => ['required', 'string'],
            'featured_image' => ['nullable', 'image', 'max:4096'],
            'is_published' => ['boolean'],
            'translations' => ['required', 'array'],
            'translations.*.title' => ['required', 'string', 'max:255'],
        ]);

        $featuredImagePath = null;
        if ($request->hasFile('featured_image')) {
            $featuredImagePath = '/storage/' . $request->file('featured_image')->store('pages', 'public');
        }

        $page = Page::create([
            'slug' => Str::slug($request->slug),
            'template' => $request->template,
            'featured_image' => $featuredImagePath,
            'is_published' => $request->boolean('is_published', true),
            'order' => Page::max('order') + 1,
            'show_in_nav' => $request->boolean('show_in_nav', false),
        ]);

        foreach ($request->translations as $locale => $tData) {
            if (!empty($tData['title'])) {
                PageTranslation::create([
                    'page_id' => $page->id,
                    'locale' => $locale,
                    'title' => $tData['title'],
                    'slug' => !empty($tData['slug']) ? Str::slug($tData['slug']) : Str::slug($tData['title']),
                    'subtitle' => $tData['subtitle'] ?? null,
                    'content' => $tData['content'] ?? null,
                    'excerpt' => $tData['excerpt'] ?? null,
                    'meta_title' => $tData['meta_title'] ?? $tData['title'],
                    'meta_description' => $tData['meta_description'] ?? null,
                    'meta_keywords' => $tData['meta_keywords'] ?? null,
                ]);
            }
        }

        ActivityLog::log('create_page', "Created page: {$page->slug}");

        return redirect()->route('admin.pages.index')->with('success', 'Page created successfully.');
    }

    public function edit(Page $page)
    {
        $languages = Language::active()->get();
        $translations = $page->translations->keyBy('locale');

        return view('admin.pages.edit', compact('page', 'languages', 'translations'));
    }

    public function update(Request $request, Page $page)
    {
        $request->validate([
            'slug' => ['required', 'string', 'max:100', 'unique:pages,slug,' . $page->id],
            'template' => ['required', 'string'],
            'featured_image' => ['nullable', 'image', 'max:4096'],
            'translations' => ['required', 'array'],
        ]);

        if ($request->hasFile('featured_image')) {
            $page->featured_image = '/storage/' . $request->file('featured_image')->store('pages', 'public');
        }

        $page->slug = Str::slug($request->slug);
        $page->template = $request->template;
        $page->is_published = $request->boolean('is_published', true);
        $page->show_in_nav = $request->boolean('show_in_nav', false);
        $page->save();

        foreach ($request->translations as $locale => $tData) {
            if (!empty($tData['title'])) {
                PageTranslation::updateOrCreate(
                    ['page_id' => $page->id, 'locale' => $locale],
                    [
                        'title' => $tData['title'],
                        'slug' => !empty($tData['slug']) ? Str::slug($tData['slug']) : Str::slug($tData['title']),
                        'subtitle' => $tData['subtitle'] ?? null,
                        'content' => $tData['content'] ?? null,
                        'excerpt' => $tData['excerpt'] ?? null,
                        'meta_title' => $tData['meta_title'] ?? $tData['title'],
                        'meta_description' => $tData['meta_description'] ?? null,
                        'meta_keywords' => $tData['meta_keywords'] ?? null,
                    ]
                );
            }
        }

        ActivityLog::log('update_page', "Updated page: {$page->slug}");

        return redirect()->route('admin.pages.index')->with('success', 'Page updated successfully.');
    }

    public function destroy(Page $page)
    {
        if (in_array($page->slug, ['home', 'contact', 'about-us'])) {
            return back()->with('error', 'Core system pages cannot be deleted.');
        }

        $slug = $page->slug;
        $page->delete();

        ActivityLog::log('delete_page', "Deleted page: {$slug}");

        return redirect()->route('admin.pages.index')->with('success', 'Page deleted successfully.');
    }
}

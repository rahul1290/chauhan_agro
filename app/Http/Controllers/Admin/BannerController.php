<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Banner;
use App\Models\BannerTranslation;
use App\Models\Language;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::with('translations')->orderBy('order', 'asc')->get();
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        $languages = Language::active()->get();
        return view('admin.banners.create', compact('languages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image', 'max:5120'],
            'mobile_image' => ['nullable', 'image', 'max:5120'],
            'button_url' => ['nullable', 'string', 'max:255'],
            'secondary_button_url' => ['nullable', 'string', 'max:255'],
            'translations' => ['required', 'array'],
            'translations.*.title' => ['required', 'string', 'max:255'],
        ]);

        $imagePath = '/storage/' . $request->file('image')->store('banners', 'public');
        $mobilePath = null;
        if ($request->hasFile('mobile_image')) {
            $mobilePath = '/storage/' . $request->file('mobile_image')->store('banners', 'public');
        }

        $banner = Banner::create([
            'page_slug' => $request->page_slug ?? 'home',
            'image_path' => $imagePath,
            'mobile_image_path' => $mobilePath,
            'button_url' => $request->button_url,
            'secondary_button_url' => $request->secondary_button_url,
            'order' => Banner::max('order') + 1,
            'is_active' => $request->boolean('is_active', true),
        ]);

        foreach ($request->translations as $locale => $tData) {
            BannerTranslation::create([
                'banner_id' => $banner->id,
                'locale' => $locale,
                'title' => $tData['title'],
                'subtitle' => $tData['subtitle'] ?? null,
                'badge_text' => $tData['badge_text'] ?? null,
                'button_text' => $tData['button_text'] ?? null,
                'secondary_button_text' => $tData['secondary_button_text'] ?? null,
            ]);
        }

        ActivityLog::log('create_banner', "Created homepage banner #{$banner->id}");

        return redirect()->route('admin.banners.index')->with('success', 'Banner created successfully.');
    }

    public function edit(Banner $banner)
    {
        $languages = Language::active()->get();
        $translations = $banner->translations->keyBy('locale');

        return view('admin.banners.edit', compact('banner', 'languages', 'translations'));
    }

    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'image' => ['nullable', 'image', 'max:5120'],
            'mobile_image' => ['nullable', 'image', 'max:5120'],
            'button_url' => ['nullable', 'string', 'max:255'],
            'secondary_button_url' => ['nullable', 'string', 'max:255'],
            'translations' => ['required', 'array'],
        ]);

        if ($request->hasFile('image')) {
            $banner->image_path = '/storage/' . $request->file('image')->store('banners', 'public');
        }

        if ($request->hasFile('mobile_image')) {
            $banner->mobile_image_path = '/storage/' . $request->file('mobile_image')->store('banners', 'public');
        }

        $banner->button_url = $request->button_url;
        $banner->secondary_button_url = $request->secondary_button_url;
        $banner->is_active = $request->boolean('is_active', true);
        $banner->save();

        foreach ($request->translations as $locale => $tData) {
            if (!empty($tData['title'])) {
                BannerTranslation::updateOrCreate(
                    ['banner_id' => $banner->id, 'locale' => $locale],
                    [
                        'title' => $tData['title'],
                        'subtitle' => $tData['subtitle'] ?? null,
                        'badge_text' => $tData['badge_text'] ?? null,
                        'button_text' => $tData['button_text'] ?? null,
                        'secondary_button_text' => $tData['secondary_button_text'] ?? null,
                    ]
                );
            }
        }

        ActivityLog::log('update_banner', "Updated banner #{$banner->id}");

        return redirect()->route('admin.banners.index')->with('success', 'Banner updated successfully.');
    }

    public function destroy(Banner $banner)
    {
        $id = $banner->id;
        $banner->delete();

        ActivityLog::log('delete_banner', "Deleted banner #{$id}");

        return redirect()->route('admin.banners.index')->with('success', 'Banner deleted successfully.');
    }
}

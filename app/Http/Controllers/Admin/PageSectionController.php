<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Language;
use App\Models\Page;
use App\Models\PageSection;
use App\Models\SectionTranslation;
use Illuminate\Http\Request;

class PageSectionController extends Controller
{
    public function index(Page $page)
    {
        $sections = $page->sections()->with('translations')->orderBy('order', 'asc')->get();
        return view('admin.pages.sections.index', compact('page', 'sections'));
    }

    public function create(Page $page)
    {
        $languages = Language::active()->get();
        $types = [
            'hero_slider' => 'Hero Banner Slider',
            'stats_counter' => 'Statistics & Counter Grid',
            'category_grid' => 'Product Category Grid',
            'product_grid' => 'Machinery Product Grid',
            'feature_cards' => 'Feature Cards (Why Choose Us)',
            'text_image' => 'Text with Image Showcase',
            'testimonials' => 'Customer Testimonials',
            'cta_banner' => 'Call to Action (CTA) Banner',
            'faq_accordion' => 'FAQ Accordion',
            'rich_text' => 'Rich Text Editorial Content',
            'contact_info' => 'Contact Info & Map Coordinates',
        ];

        return view('admin.pages.sections.create', compact('page', 'languages', 'types'));
    }

    public function store(Request $request, Page $page)
    {
        $request->validate([
            'type' => ['required', 'string'],
            'translations' => ['required', 'array'],
        ]);

        $maxOrder = $page->sections()->max('order') ?? 0;

        $section = PageSection::create([
            'page_id' => $page->id,
            'type' => $request->type,
            'order' => $maxOrder + 1,
            'is_active' => $request->boolean('is_active', true),
            'settings_json' => $request->settings ?? [],
        ]);

        foreach ($request->translations as $locale => $tData) {
            $dataJson = null;
            if (!empty($tData['data_json']) && is_string($tData['data_json'])) {
                $decoded = json_decode($tData['data_json'], true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $dataJson = $decoded;
                }
            } elseif (isset($tData['data_json']) && is_array($tData['data_json'])) {
                $dataJson = $tData['data_json'];
            }

            SectionTranslation::create([
                'section_id' => $section->id,
                'locale' => $locale,
                'title' => $tData['title'] ?? null,
                'subtitle' => $tData['subtitle'] ?? null,
                'content' => $tData['content'] ?? null,
                'button_text' => $tData['button_text'] ?? null,
                'button_url' => $tData['button_url'] ?? null,
                'data_json' => $dataJson,
            ]);
        }

        ActivityLog::log('create_section', "Added section ({$section->type}) to page {$page->slug}");

        return redirect()->route('admin.pages.sections.index', $page)->with('success', 'Section added successfully.');
    }

    public function edit(Page $page, PageSection $section)
    {
        $languages = Language::active()->get();
        $translations = $section->translations->keyBy('locale');

        return view('admin.pages.sections.edit', compact('page', 'section', 'languages', 'translations'));
    }

    public function update(Request $request, Page $page, PageSection $section)
    {
        $request->validate([
            'translations' => ['required', 'array'],
        ]);

        $section->is_active = $request->boolean('is_active', true);
        if ($request->has('settings')) {
            $section->settings_json = $request->settings;
        }
        $section->save();

        foreach ($request->translations as $locale => $tData) {
            $dataJson = null;
            if (!empty($tData['data_json']) && is_string($tData['data_json'])) {
                $decoded = json_decode($tData['data_json'], true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $dataJson = $decoded;
                }
            } elseif (isset($tData['data_json']) && is_array($tData['data_json'])) {
                $dataJson = $tData['data_json'];
            }

            SectionTranslation::updateOrCreate(
                ['section_id' => $section->id, 'locale' => $locale],
                [
                    'title' => $tData['title'] ?? null,
                    'subtitle' => $tData['subtitle'] ?? null,
                    'content' => $tData['content'] ?? null,
                    'button_text' => $tData['button_text'] ?? null,
                    'button_url' => $tData['button_url'] ?? null,
                    'data_json' => $dataJson,
                ]
            );
        }

        ActivityLog::log('update_section', "Updated section #{$section->id} on page {$page->slug}");

        return redirect()->route('admin.pages.sections.index', $page)->with('success', 'Section updated successfully.');
    }

    public function reorder(Request $request, Page $page, PageSection $section, string $direction)
    {
        $currentOrder = $section->order;

        if ($direction === 'up') {
            $swapSection = $page->sections()->where('order', '<', $currentOrder)->orderBy('order', 'desc')->first();
        } else {
            $swapSection = $page->sections()->where('order', '>', $currentOrder)->orderBy('order', 'asc')->first();
        }

        if ($swapSection) {
            $section->order = $swapSection->order;
            $swapSection->order = $currentOrder;
            $section->save();
            $swapSection->save();
        }

        return back()->with('success', 'Section order updated.');
    }

    public function destroy(Page $page, PageSection $section)
    {
        $section->delete();
        ActivityLog::log('delete_section', "Deleted section from page {$page->slug}");

        return redirect()->route('admin.pages.sections.index', $page)->with('success', 'Section removed.');
    }
}

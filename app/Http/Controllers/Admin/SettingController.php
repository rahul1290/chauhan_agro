<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $allSettings = Setting::all()->keyBy('key');
        $homePage = \App\Models\Page::where('slug', 'home')->first();
        $homeSections = $homePage ? $homePage->sections()->with('translations')->orderBy('order')->get() : collect();

        return view('admin.settings.index', compact('allSettings', 'homeSections', 'homePage'));
    }

    public function update(Request $request)
    {
        $inputs = $request->except(['_token', '_method']);

        // Handle under_construction_enabled checkbox correctly
        if ($request->has('under_construction_section_present')) {
            $inputs['under_construction_enabled'] = $request->boolean('under_construction_enabled') ? '1' : '0';
        }

        // Handle animation settings checkboxes correctly
        if ($request->has('animation_settings_present')) {
            $inputs['animations_enabled'] = $request->boolean('animations_enabled') ? '1' : '0';
            $inputs['animation_stagger_enabled'] = $request->boolean('animation_stagger_enabled') ? '1' : '0';
            $inputs['admin_animations_enabled'] = $request->boolean('admin_animations_enabled') ? '1' : '0';
        }

        // Handle section-wise animation settings
        if ($request->has('section_animations') && is_array($request->section_animations)) {
            foreach ($request->section_animations as $secId => $secData) {
                $sec = \App\Models\PageSection::find($secId);
                if ($sec) {
                    $settings = $sec->settings_json ?? [];
                    $settings['animation_effect'] = $secData['effect'] ?? '';
                    $settings['animation_duration'] = $secData['duration'] ?? '';
                    $sec->settings_json = $settings;
                    $sec->save();
                }
            }
            unset($inputs['section_animations']);
        }

        foreach ($inputs as $key => $value) {
            Setting::set($key, $value);
        }

        ActivityLog::log('update_settings', "Updated website settings and business information");

        return back()->with('success', 'Website settings updated successfully.');
    }

    /**
     * Quick toggle for Under Construction mode.
     */
    public function toggleUnderConstruction(Request $request)
    {
        $current = (bool) Setting::get('under_construction_enabled', false);
        $newVal = $current ? '0' : '1';
        Setting::set('under_construction_enabled', $newVal, 'maintenance');

        $statusMsg = $newVal === '1' ? 'activated (general visitors see Under Construction page)' : 'deactivated (website is now live for all visitors)';
        ActivityLog::log('toggle_under_construction', "Under Construction mode {$statusMsg}");

        return back()->with('success', "Under Construction mode {$statusMsg}.");
    }
}

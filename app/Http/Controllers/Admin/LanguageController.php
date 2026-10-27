<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Language;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function index()
    {
        $languages = Language::orderBy('sort_order', 'asc')->get();
        return view('admin.languages.index', compact('languages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:10', 'unique:languages,code'],
            'name' => ['required', 'string', 'max:100'],
            'native_name' => ['nullable', 'string', 'max:100'],
            'flag' => ['nullable', 'string', 'max:20'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $validated['is_active'] = true;
        $validated['is_default'] = false;
        $validated['sort_order'] = $validated['sort_order'] ?? (Language::max('sort_order') + 1);

        Language::create($validated);
        ActivityLog::log('create_language', "Added new language: {$validated['name']} ({$validated['code']})");

        return redirect()->route('admin.languages.index')->with('success', 'Language added successfully.');
    }

    public function update(Request $request, Language $language)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'native_name' => ['nullable', 'string', 'max:100'],
            'flag' => ['nullable', 'string', 'max:20'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $language->update($validated);
        ActivityLog::log('update_language', "Updated language: {$language->name}");

        return redirect()->route('admin.languages.index')->with('success', 'Language updated successfully.');
    }

    public function toggleStatus(Language $language)
    {
        if ($language->is_default && $language->is_active) {
            return back()->with('error', 'The default system language cannot be deactivated.');
        }

        $language->is_active = !$language->is_active;
        $language->save();

        ActivityLog::log('toggle_language', "Language {$language->name} active status set to: " . ($language->is_active ? 'Active' : 'Inactive'));

        return back()->with('success', "Language {$language->name} status updated.");
    }

    public function setDefault(Language $language)
    {
        if (!$language->is_active) {
            return back()->with('error', 'Cannot set an inactive language as default. Please activate it first.');
        }

        Language::where('is_default', true)->update(['is_default' => false]);
        $language->is_default = true;
        $language->save();

        ActivityLog::log('set_default_language', "Set {$language->name} as the default language");

        return back()->with('success', "{$language->name} is now the default language.");
    }
}

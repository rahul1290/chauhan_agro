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
        return view('admin.settings.index', compact('allSettings'));
    }

    public function update(Request $request)
    {
        $inputs = $request->except(['_token', '_method']);

        foreach ($inputs as $key => $value) {
            Setting::set($key, $value);
        }

        ActivityLog::log('update_settings', "Updated website settings and business information");

        return back()->with('success', 'Website settings updated successfully.');
    }
}

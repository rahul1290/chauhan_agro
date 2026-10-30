<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\PromotionalPopup;
use App\Models\Setting;
use Illuminate\Http\Request;

class PopupController extends Controller
{
    /**
     * Display promotional popup settings.
     */
    public function index()
    {
        $popup = PromotionalPopup::firstOrCreate([], [
            'title' => 'EIMA International Agricultural Machinery Exhibition 2026',
            'image_path' => 'assets/images/popups/exhibition-banner.jpg',
            'link_url' => '/en/contact-us',
            'target_blank' => false,
            'border_color' => '#16a34a',
            'display_frequency' => 'always',
            'display_delay' => 1,
            'is_active' => true,
        ]);

        $targetPages = Setting::get('promo_popup_target_pages', 'home');

        return view('admin.popup.index', compact('popup', 'targetPages'));
    }

    /**
     * Update promotional popup settings.
     */
    public function update(Request $request)
    {
        $popup = PromotionalPopup::first();
        if (!$popup) {
            $popup = new PromotionalPopup();
        }

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'link_url' => ['nullable', 'string', 'max:500'],
            'border_color' => ['required', 'string', 'max:30'],
            'display_frequency' => ['required', 'in:always,session,daily'],
            'display_delay' => ['required', 'integer', 'min:0', 'max:60'],
            'target_pages' => ['nullable', 'in:home,all'],
        ]);

        if ($request->hasFile('image')) {
            $imagePath = 'storage/' . $request->file('image')->store('popups', 'public');
            $popup->image_path = $imagePath;
        }

        $popup->title = $request->input('title');
        $popup->link_url = $request->input('link_url');
        $popup->target_blank = $request->boolean('target_blank');
        $popup->border_color = $request->input('border_color', '#16a34a');
        $popup->display_frequency = $request->input('display_frequency', 'always');
        $popup->display_delay = (int) $request->input('display_delay', 1);
        $popup->is_active = $request->boolean('is_active');
        $popup->save();

        // Save target display location (home vs all)
        Setting::set('promo_popup_target_pages', $request->input('target_pages', 'home'), 'popup');

        ActivityLog::log('update_popup', "Updated promotional popup settings: '{$popup->title}' (target: {$request->input('target_pages', 'home')})");

        return back()->with('success', 'Promotional popup settings updated successfully!');
    }

    /**
     * Quick toggle active / inactive status.
     */
    public function toggleStatus(Request $request)
    {
        $popup = PromotionalPopup::first();
        if (!$popup) {
            $popup = PromotionalPopup::create([
                'title' => 'EIMA International Agricultural Machinery Exhibition 2026',
                'image_path' => 'assets/images/popups/exhibition-banner.jpg',
                'link_url' => '/en/contact-us',
                'target_blank' => false,
                'border_color' => '#16a34a',
                'display_frequency' => 'always',
                'display_delay' => 1,
                'is_active' => true,
            ]);
        }

        $popup->is_active = !$popup->is_active;
        $popup->save();

        $target = Setting::get('promo_popup_target_pages', 'home');
        $targetDesc = $target === 'home' ? 'Homepage' : 'all pages';
        $stateMsg = $popup->is_active ? "activated (now displaying on {$targetDesc} on every refresh)" : 'deactivated (hidden from website)';
        ActivityLog::log('toggle_popup', "Popup was {$stateMsg}");

        return back()->with('success', "Popup was {$stateMsg} successfully.");
    }
}

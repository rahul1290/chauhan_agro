<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUnderConstruction
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // 1. Always allow admin panel, authentication, assets, and storage routes
        if (
            $request->is('admin*') ||
            $request->is('login*') ||
            $request->is('logout*') ||
            $request->is('assets*') ||
            $request->is('storage*') ||
            $request->is('client-access*')
        ) {
            return $next($request);
        }

        // 2. Check if Under Construction mode is enabled in site settings
        $isEnabled = (bool) Setting::get('under_construction_enabled', false);
        if (!$isEnabled) {
            return $next($request);
        }

        // 3. Always allow authenticated administrators and staff
        if (Auth::check()) {
            return $next($request);
        }

        $secretKey = Setting::get('under_construction_secret', 'chauhan2026');

        // 4. Check if client is visiting with the secret query parameter:
        // e.g. ?client_access=chauhan2026 or ?preview=chauhan2026
        $queryKey = $request->query('client_access') ?? $request->query('preview');
        if ($queryKey && trim($queryKey) === trim($secretKey)) {
            session(['client_preview_access' => true]);
            cookie()->queue('client_preview_access', $secretKey, 60 * 24 * 30); // 30-day access cookie
            
            // Redirect to clean URL without query parameter for a polished client experience
            return redirect($request->url());
        }

        // 5. Check if client has active session or valid 30-day preview cookie
        if (session('client_preview_access') === true) {
            return $next($request);
        }

        if ($request->cookie('client_preview_access') === $secretKey) {
            session(['client_preview_access' => true]);
            return $next($request);
        }

        // 6. Otherwise: display the Under Construction view to the general public
        $siteName = Setting::get('site_name', 'Chauhan Agro Industries');
        $phone = Setting::get('under_construction_phone', Setting::get('phone_primary', '1800-212-9000'));
        $whatsapp = Setting::get('under_construction_whatsapp', Setting::get('whatsapp_number', '+919254016570'));
        $email = Setting::get('under_construction_email', Setting::get('email_primary', 'sales@chauhanagroindustries.com'));
        $title = Setting::get('under_construction_title', 'Website Under Construction');
        $message = Setting::get('under_construction_message', 'We are currently upgrading our digital presence to serve farmers, dealers, and global agricultural partners better. Our full machinery catalogue and online portal will launch soon.');
        $address = Setting::get('factory_address', 'Chauhan Agro Industries, GT Road, Karnal, Haryana, India');

        return response()->view('frontend.under_construction', compact(
            'siteName',
            'title',
            'message',
            'phone',
            'whatsapp',
            'email',
            'address',
            'secretKey'
        ), 503);
    }
}

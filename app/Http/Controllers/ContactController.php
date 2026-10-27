<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Models\Page;
use App\Models\Product;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function show()
    {
        $page = Page::where('slug', 'contact')->with('translations')->first();
        $products = Product::where('is_active', true)->with('translations')->get();

        return view('frontend.contact', compact('page', 'products'));
    }

    public function submit(Request $request)
    {
        // Anti-spam honeypot
        if ($request->filled('website_url')) {
            return back()->with('success', 'Your inquiry has been submitted successfully.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['required', 'string', 'max:30'],
            'company' => ['nullable', 'string', 'max:150'],
            'country' => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'product_id' => ['nullable', 'exists:products,id'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:3000'],
        ]);

        $validated['locale'] = app()->getLocale();
        $validated['ip_address'] = $request->ip();
        $validated['status'] = 'unread';

        $enquiry = Enquiry::create($validated);

        $successMsg = app()->getLocale() === 'hi'
            ? 'धन्यवाद! आपकी पूछताछ सफलतापूर्वक दर्ज कर ली गई है। हमारी टीम जल्द ही आपसे संपर्क करेगी।'
            : 'Thank you! Your machinery inquiry has been registered successfully. Our technical sales representative will contact you shortly.';

        return back()->with('success', $successMsg);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Enquiry;
use App\Models\Page;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_pages' => Page::where('is_published', true)->count(),
            'total_products' => Product::where('is_active', true)->count(),
            'total_categories' => ProductCategory::where('is_active', true)->count(),
            'total_enquiries' => Enquiry::count(),
            'unread_enquiries' => Enquiry::where('status', 'unread')->count(),
            'pending_followups' => Enquiry::where('status', 'read')->count(),
        ];

        $recentEnquiries = Enquiry::with('product')->latest()->take(6)->get();
        $recentActivities = ActivityLog::with('user')->latest()->take(8)->get();
        $recentProducts = Product::with(['category', 'translations'])->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentEnquiries', 'recentActivities', 'recentProducts'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Enquiry;
use App\Models\EnquiryNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnquiryController extends Controller
{
    public function index(Request $request)
    {
        $query = Enquiry::with('product');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        $enquiries = $query->latest()->paginate(15);
        $counts = [
            'all' => Enquiry::count(),
            'unread' => Enquiry::where('status', 'unread')->count(),
            'read' => Enquiry::where('status', 'read')->count(),
            'contacted' => Enquiry::where('status', 'contacted')->count(),
            'resolved' => Enquiry::where('status', 'resolved')->count(),
        ];

        return view('admin.enquiries.index', compact('enquiries', 'counts'));
    }

    public function show(Enquiry $enquiry)
    {
        if ($enquiry->status === 'unread') {
            $enquiry->update(['status' => 'read']);
        }

        $enquiry->load(['product.translations', 'notes.user']);

        return view('admin.enquiries.show', compact('enquiry'));
    }

    public function updateStatus(Request $request, Enquiry $enquiry)
    {
        $request->validate([
            'status' => ['required', 'in:unread,read,contacted,resolved,spam'],
        ]);

        $enquiry->update(['status' => $request->status]);
        ActivityLog::log('update_enquiry_status', "Changed status of enquiry #{$enquiry->id} to {$request->status}");

        return back()->with('success', 'Enquiry status updated successfully.');
    }

    public function addNote(Request $request, Enquiry $enquiry)
    {
        $request->validate([
            'note' => ['required', 'string', 'max:2000'],
        ]);

        EnquiryNote::create([
            'enquiry_id' => $enquiry->id,
            'user_id' => Auth::id(),
            'note' => $request->note,
        ]);

        ActivityLog::log('add_enquiry_note', "Added follow-up note on enquiry #{$enquiry->id}");

        return back()->with('success', 'Internal note recorded.');
    }

    public function exportCsv()
    {
        $enquiries = Enquiry::with('product')->latest()->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="agrovision-enquiries-' . date('Y-m-d') . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($enquiries) {
            $handle = fopen('php://output', 'w');
            // Add UTF-8 BOM for Excel compatibility
            fputs($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['ID', 'Date', 'Customer Name', 'Email', 'Phone', 'Company', 'Country', 'City', 'Product', 'Subject', 'Status', 'Language']);

            foreach ($enquiries as $e) {
                fputcsv($handle, [
                    $e->id,
                    $e->created_at->format('Y-m-d H:i'),
                    $e->name,
                    $e->email,
                    $e->phone ?? '-',
                    $e->company ?? '-',
                    $e->country ?? '-',
                    $e->city ?? '-',
                    $e->product ? $e->product->name : 'General Inquiry',
                    $e->subject,
                    ucfirst($e->status),
                    strtoupper($e->locale),
                ]);
            }

            fclose($handle);
        };

        ActivityLog::log('export_enquiries', "Exported customer enquiries CSV");

        return response()->stream($callback, 200, $headers);
    }
}

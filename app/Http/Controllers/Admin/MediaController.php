<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $query = Media::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")->orWhere('file_name', 'like', "%{$search}%");
        }

        if ($request->filled('type')) {
            if ($request->type === 'image') {
                $query->where('mime_type', 'like', 'image/%');
            } elseif ($request->type === 'pdf') {
                $query->where('mime_type', 'application/pdf');
            }
        }

        $mediaItems = $query->latest()->paginate(24);

        return view('admin.media.index', compact('mediaItems'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'files' => ['required', 'array'],
            'files.*' => [
                'required',
                'file',
                'mimes:jpeg,png,jpg,webp,svg,pdf,doc,docx,xls,xlsx',
                'max:15360', // 15MB limit
            ],
        ]);

        $uploaded = 0;
        foreach ($request->file('files') as $file) {
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $safeName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '-' . time() . '.' . $extension;

            $path = $file->storeAs('uploads', $safeName, 'public');

            Media::create([
                'name' => pathinfo($originalName, PATHINFO_FILENAME),
                'file_name' => $safeName,
                'file_path' => '/storage/' . $path,
                'disk' => 'public',
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'alt_text' => pathinfo($originalName, PATHINFO_FILENAME),
            ]);

            $uploaded++;
        }

        ActivityLog::log('upload_media', "Uploaded {$uploaded} media assets");

        return back()->with('success', "{$uploaded} file(s) uploaded successfully.");
    }

    public function destroy(Media $media)
    {
        $relative = str_replace('/storage/', '', $media->file_path);
        if (Storage::disk($media->disk)->exists($relative)) {
            Storage::disk($media->disk)->delete($relative);
        }

        $name = $media->name;
        $media->delete();

        ActivityLog::log('delete_media', "Deleted media: {$name}");

        return back()->with('success', 'Media file deleted successfully.');
    }
}

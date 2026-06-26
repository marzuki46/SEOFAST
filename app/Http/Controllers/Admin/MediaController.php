<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    /**
     * Display a listing of the media files.
     */
    public function index()
    {
        // 10 items per row on large screen means we want a multiple of 10 for pagination. Let's do 40 per page.
        $media = Media::orderBy('created_at', 'desc')->paginate(40);

        return view('admin.media.index', compact('media'));
    }

    /**
     * Store newly uploaded media files.
     */
    public function store(Request $request)
    {
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'image|mimes:jpeg,png,jpg,webp,gif|max:5120' // 5MB max
        ]);

        $uploaded = [];
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                // Generate safe filename
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $filename = Str::slug($originalName) . '-' . time() . '.' . $extension;
                
                $path = $file->storeAs('content-images', $filename, 'public');
                $url = Storage::disk('public')->url($path);
                
                $media = Media::create([
                    'filename' => $filename,
                    'path' => $path,
                    'url' => $url,
                    'title' => ucwords(str_replace('-', ' ', Str::slug($originalName))),
                    'alt_text' => ucwords(str_replace('-', ' ', Str::slug($originalName))),
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                ]);

                $uploaded[] = $media;
            }
        }

        return back()->with('success', count($uploaded) . ' image(s) uploaded successfully.');
    }

    /**
     * Update the specified media file.
     */
    public function update(Request $request, Media $medium)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'alt_text' => 'nullable|string|max:255',
        ]);

        $medium->update($request->only(['title', 'alt_text']));

        return back()->with('success', 'Media details updated successfully.');
    }

    /**
     * Remove the specified media file.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:media,id'
        ]);

        $media = Media::findOrFail($request->input('id'));

        if (Storage::disk('public')->exists($media->path)) {
            Storage::disk('public')->delete($media->path);
        }
        
        $media->delete();

        return back()->with('success', 'Media file deleted successfully.');
    }
}

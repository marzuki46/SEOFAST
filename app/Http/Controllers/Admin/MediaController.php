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

    public function store(Request $request)
    {
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'image|mimes:jpeg,png,jpg,webp,gif|max:10240' // 10MB max initial
        ]);

        $uploaded = [];
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $slug = Str::slug($originalName);

                $result = app(\App\Services\ImageService::class)->processUpload($file, 'content-images', 800);

                if ($result) {
                    $media = Media::create([
                        'filename' => basename($result['path']),
                        'path'     => $result['path'],
                        'url'      => $result['url'],
                        'title'    => ucwords(str_replace('-', ' ', $slug)),
                        'alt_text' => ucwords(str_replace('-', ' ', $slug)),
                        'size'     => $result['size'],
                        'mime_type'=> 'image/webp',
                    ]);
                    $uploaded[] = $media;
                }
            }
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'uploaded' => $uploaded]);
        }

        return back()->with('success', count($uploaded) . ' image(s) processed and uploaded successfully.');
    }

    /**
     * Update the specified media file.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'alt_text' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $medium = Media::findOrFail($id);
        $medium->update($request->only(['title', 'alt_text', 'description']));

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Media details updated successfully.', 'media' => $medium]);
        }

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

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Media file deleted successfully.']);
        }

        return back()->with('success', 'Media file deleted successfully.');
    }
}

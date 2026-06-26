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
                // Generate safe filename (always webp now)
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $filename = Str::slug($originalName) . '-' . time() . '.webp';
                
                // Compress and Resize using GD
                $tempWebpPath = $this->compressAndResizeToWebp($file, 800);
                
                if ($tempWebpPath) {
                    // Store the converted webp file
                    $path = 'content-images/' . $filename;
                    Storage::disk('public')->put($path, file_get_contents($tempWebpPath));
                    $url = Storage::disk('public')->url($path);
                    
                    $media = Media::create([
                        'filename' => $filename,
                        'path' => $path,
                        'url' => $url,
                        'title' => ucwords(str_replace('-', ' ', Str::slug($originalName))),
                        'alt_text' => ucwords(str_replace('-', ' ', Str::slug($originalName))),
                        'size' => filesize($tempWebpPath),
                        'mime_type' => 'image/webp',
                    ]);

                    $uploaded[] = $media;
                    unlink($tempWebpPath); // clean up temp file
                } else {
                    // Fallback to original if conversion fails
                    $fallbackFilename = Str::slug($originalName) . '-' . time() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('content-images', $fallbackFilename, 'public');
                    $url = Storage::disk('public')->url($path);
                    
                    $media = Media::create([
                        'filename' => $fallbackFilename,
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
        }

        return back()->with('success', count($uploaded) . ' image(s) processed and uploaded successfully.');
    }

    /**
     * Helper method to resize and convert image to WebP using native GD
     */
    private function compressAndResizeToWebp($file, $maxSize = 800)
    {
        $info = @getimagesize($file->getPathname());
        if (!$info) return false;

        $width = $info[0];
        $height = $info[1];
        $mime = $info['mime'];

        // Calculate new dimensions
        $newWidth = $width;
        $newHeight = $height;
        if ($width > $maxSize || $height > $maxSize) {
            if ($width > $height) {
                $newWidth = $maxSize;
                $newHeight = intval($height * ($maxSize / $width));
            } else {
                $newHeight = $maxSize;
                $newWidth = intval($width * ($maxSize / $height));
            }
        }

        // Create image resource
        switch ($mime) {
            case 'image/jpeg': $image = @imagecreatefromjpeg($file->getPathname()); break;
            case 'image/png': $image = @imagecreatefrompng($file->getPathname()); break;
            case 'image/gif': $image = @imagecreatefromgif($file->getPathname()); break;
            case 'image/webp': $image = @imagecreatefromwebp($file->getPathname()); break;
            default: return false;
        }

        if (!$image) return false;

        // Resize
        $newImage = imagecreatetruecolor($newWidth, $newHeight);
        
        // Preserve transparency
        if ($mime == 'image/png' || $mime == 'image/webp') {
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
            $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
            imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);
        }

        imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        // Save as WEBP
        $tempPath = sys_get_temp_dir() . '/' . uniqid('img_') . '.webp';
        imagewebp($newImage, $tempPath, 80); // 80% quality for good compression
        
        imagedestroy($image);
        imagedestroy($newImage);

        return $tempPath;
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

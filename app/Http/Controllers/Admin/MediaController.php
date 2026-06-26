<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        // Get all files from public/content-images directory
        $files = Storage::disk('public')->files('content-images');
        
        $media = [];
        foreach ($files as $file) {
            $media[] = [
                'name' => basename($file),
                'url' => Storage::disk('public')->url($file),
                'path' => $file,
                'size' => $this->formatBytes(Storage::disk('public')->size($file)),
                'last_modified' => Storage::disk('public')->lastModified($file)
            ];
        }

        // Sort by last modified, newest first
        usort($media, function($a, $b) {
            return $b['last_modified'] <=> $a['last_modified'];
        });

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
                $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) 
                          . '-' . time() 
                          . '.' . $file->getClientOriginalExtension();
                
                $path = $file->storeAs('content-images', $filename, 'public');
                $uploaded[] = Storage::disk('public')->url($path);
            }
        }

        return back()->with('success', count($uploaded) . ' image(s) uploaded successfully.');
    }

    /**
     * Remove the specified media file.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'path' => 'required|string'
        ]);

        $path = $request->input('path');

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
            return back()->with('success', 'Media file deleted successfully.');
        }

        return back()->with('error', 'File not found.');
    }

    /**
     * Helper to format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2) { 
        $units = array('B', 'KB', 'MB', 'GB', 'TB'); 
      
        $bytes = max($bytes, 0); 
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
        $pow = min($pow, count($units) - 1); 
      
        $bytes /= pow(1024, $pow); 
      
        return round($bytes, $precision) . ' ' . $units[$pow]; 
    } 
}

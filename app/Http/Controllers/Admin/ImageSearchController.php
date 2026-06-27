<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ImageSearchController extends Controller
{
    /**
     * View the image selection page for a specific content.
     */
    public function index(Content $content)
    {

        return view('admin.content.images', compact('content'));
    }

    /**
     * Search images via Unsplash API (Demo integration).
     */
    public function search(Request $request, Content $content)
    {

        $query = $request->input('query', $content->target_keyword);
        
        // In a real scenario, use config('services.unsplash.client_id')
        // For demo purposes, we will return some placeholder images matching the query
        $images = [];
        
        // Using unspash source URL for demo since we might not have a real API key configured
        for ($i = 0; $i < 12; $i++) {
            $images[] = [
                'id' => 'img_' . rand(1000, 9999),
                'url' => "https://loremflickr.com/800/600/".urlencode(str_replace(' ', ',', $query))."?lock=" . rand(1, 1000),
                'thumb' => "https://loremflickr.com/400/300/".urlencode(str_replace(' ', ',', $query))."?lock=" . rand(1, 1000),
                'author' => 'Free Provider',
                'alt_text' => $query . ' related image ' . ($i + 1),
            ];
        }

        return response()->json(['images' => $images]);
    }

    /**
     * Select and attach image to content.
     */
    public function select(Request $request, Content $content)
    {

        $request->validate([
            'image_url' => 'required|url',
            'alt_text' => 'nullable|string'
        ]);

        // Phase 4 Logic: Here you would download the image to S3, resize, convert to WebP
        // For this implementation, we will save the external URL directly
        
        $content->update([
            'featured_image_url' => $request->image_url,
        ]);

        return redirect()->route('admin.content.edit', $content->id)
            ->with('success', 'Featured image successfully attached! AI will use this in Phase 4 generation.');
    }
}

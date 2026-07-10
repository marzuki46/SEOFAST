<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Show the application landing page.
     */
    public function index(): View
    {
        // Get the latest 3 published articles with actual content (not UUID/empty)
        $recentPosts = Content::where('status', 'published')
            ->whereNotNull('body_raw')
            ->where('published_at', '<=', now())
            ->where('body_raw', '!=', '{"id":""}')
            ->where('body_raw', '!=', '{"en":""}')
            ->where(function ($q) {
                $q->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(body_raw, '$.id')) != ''")
                  ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(body_raw, '$.en')) != ''");
            })
            ->with('siloBlueprint')
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        // Get active products for the products section
        $products = Product::where('is_active', true)
            ->with('categories')
            ->latest()
            ->take(6)
            ->get();

        // Get featured/best-seller product
        $featuredProduct = Product::where('is_active', true)
            ->where('is_featured', true)
            ->first()
            ?? Product::where('is_active', true)
                ->orderBy('purchase_count', 'desc')
                ->first();

        return view('home', compact('recentPosts', 'products', 'featuredProduct'));
    }
}

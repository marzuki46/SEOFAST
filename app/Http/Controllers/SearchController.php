<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Page;
use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');

        if (!$q || trim($q) === '') {
            return redirect()->back()->with('error', 'Please enter a search term.');
        }

        $search = trim($q);
        $locale = app()->getLocale();

        // Search blog posts
        $posts = Content::where('status', 'published')
            ->whereNotNull('body_raw')
            ->where('published_at', '<=', now())
            ->where(function ($query) use ($search) {
                $query->where('meta_title', 'like', "%{$search}%")
                    ->orWhere('target_keyword', 'like', "%{$search}%")
                    ->orWhere('body_raw', 'like', "%{$search}%");
            })
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get();

        // Search products
        $products = Product::where('is_active', true)
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Search static pages
        $pages = Page::where('is_published', true)
            ->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('html_content', 'like', "%{$search}%")
                    ->orWhere('meta_title', 'like', "%{$search}%")
                    ->orWhere('meta_description', 'like', "%{$search}%");
            })
            ->orderBy('title')
            ->take(5)
            ->get();

        $totalResults = $posts->count() + $products->count() + $pages->count();

        $permalinkBlog = \App\Models\SystemSetting::get('permalink_blog', 'blog');
        $permalinkProduct = \App\Models\SystemSetting::get('permalink_product', 'produk');

        return view('search.index', compact(
            'q', 'search', 'posts', 'products', 'pages',
            'totalResults', 'permalinkBlog', 'permalinkProduct', 'locale'
        ));
    }
}

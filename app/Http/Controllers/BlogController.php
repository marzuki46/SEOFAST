<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\SiloBlueprint;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    /**
     * Display the blog home page with articles and categories.
     */
    public function index(Request $request): View
    {
        $query = Content::where('status', 'published')
            ->whereNotNull('body_raw')
            ->where('published_at', '<=', now())
            ->where('body_raw', '!=', '{"id":""}')
            ->where('body_raw', '!=', '{"en":""}')
            ->where(function($q) {
                $q->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(body_raw, '$.id')) != ''")
                  ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(body_raw, '$.en')) != ''");
            })
            ->orderBy('published_at', 'desc');

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('meta_title', 'like', "%{$search}%")
                  ->orWhere('target_keyword', 'like', "%{$search}%")
                  ->orWhere('body_raw', 'like', "%{$search}%");
            });
        }

        $posts = $query->paginate(6);

        $categories = SiloBlueprint::withCount(['contents' => function ($query) {
            $query->where('status', 'published')
                  ->whereNotNull('body_raw')
                  ->where(function($q) {
                      $q->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(body_raw, '$.id')) != ''")
                        ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(body_raw, '$.en')) != ''");
                  });
        }])->having('contents_count', '>', 0)->get();

        $recentPosts = Content::where('status', 'published')
            ->whereNotNull('body_raw')
            ->where('published_at', '<=', now())
            ->where(function($q) {
                $q->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(body_raw, '$.id')) != ''")
                  ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(body_raw, '$.en')) != ''");
            })
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get();

        return view('blog.index', compact('posts', 'categories', 'recentPosts'));
    }

    /**
     * Display a single blog post.
     */
    public function show(string $slug): View
    {
        // Find the post by slug in the current locale
        $query = Content::where(function($q) use ($slug) {
                $q->where('slug', $slug)
                  ->orWhere('slug', 'LIKE', '%"id":"' . $slug . '"%')
                  ->orWhere('slug', 'LIKE', '%"en":"' . $slug . '"%');
            });
            
        if (auth()->check()) {
            // Admins can preview drafts and unpublished posts
            $post = $query->first();
        } else {
            // Public visitors only see published posts
            $post = $query->where('status', 'published')
                          ->where('published_at', '<=', now())
                          ->first();
        }

        // If not found, abort 404
        if (!$post) {
            abort(404);
        }

        // Get related posts (only those with actual content, excluding empty/blueprint)
        $relatedPosts = Content::where('silo_blueprint_id', $post->silo_blueprint_id)
            ->where('id', '!=', $post->id)
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->whereNotNull('body_raw')
            ->where(function($q) {
                $q->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(body_raw, '$.id')) != ''")
                  ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(body_raw, '$.en')) != ''");
            })
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        $categories = SiloBlueprint::withCount(['contents' => function ($query) {
            $query->where('status', 'published')
                  ->whereNotNull('body_raw')
                  ->where(function($q) {
                      $q->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(body_raw, '$.id')) != ''")
                        ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(body_raw, '$.en')) != ''");
                  });
        }])->having('contents_count', '>', 0)->get();

        // Fetch parent categories or silo blueprint info
        $category = $post->siloBlueprint;
        
        $isPreview = false;

        return view('blog.show', compact('post', 'relatedPosts', 'categories', 'category', 'isPreview'));
    }

    /**
     * Preview a single blog post (bypasses auth and publication status, but adds noindex).
     */
    public function preview(string $slug): View
    {
        $post = Content::where(function($q) use ($slug) {
                $q->where('slug', $slug)
                  ->orWhere('slug', 'LIKE', '%"id":"' . $slug . '"%')
                  ->orWhere('slug', 'LIKE', '%"en":"' . $slug . '"%');
            })->first();

        if (!$post) {
            abort(404);
        }

        $relatedPosts = Content::where('silo_blueprint_id', $post->silo_blueprint_id)
            ->where('id', '!=', $post->id)
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->whereNotNull('body_raw')
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        $categories = SiloBlueprint::withCount(['contents' => function ($query) {
            $query->where('status', 'published');
        }])->having('contents_count', '>', 0)->get();

        $category = $post->siloBlueprint;
        
        $isPreview = true;

        return view('blog.show', compact('post', 'relatedPosts', 'categories', 'category', 'isPreview'));
    }

    /**
     * Display blog posts filtered by category (silo).
     */
    public function category(string $slug): View
    {
        // Find silo blueprint that matches the slug on-the-fly
        $category = SiloBlueprint::all()->first(function ($silo) use ($slug) {
            return $silo->slug === $slug;
        });

        if (!$category) {
            abort(404, 'Category not found');
        }

        $posts = Content::where('silo_blueprint_id', $category->id)
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->whereNotNull('body_raw')
            ->where(function($q) {
                $q->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(body_raw, '$.id')) != ''")
                  ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(body_raw, '$.en')) != ''");
            })
            ->orderBy('published_at', 'desc')
            ->paginate(6);

        $categories = SiloBlueprint::withCount(['contents' => function ($query) {
            $query->where('status', 'published');
        }])->get();
        $recentPosts = Content::where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get();

        return view('blog.category', compact('category', 'posts', 'categories', 'recentPosts'));
    }
}

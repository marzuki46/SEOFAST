<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        $page = Page::where('is_homepage', true)->first();
        if (!$page) {
            // Fallback default home
            return app()->make(HomeController::class)->index();
        }
        return view('pages.show', compact('page'));
    }

    public function show($slug)
    {
        $page = Page::where('slug', $slug)->first();
        
        if ($page) {
            return view('pages.show', compact('page'));
        }

        // If no exact match, check if this slug acts as a folder/archive
        $childPages = Page::where('slug', 'like', $slug . '/%')->orderBy('created_at', 'desc')->paginate(12);
        
        if ($childPages->count() > 0) {
            return view('pages.archive', compact('slug', 'childPages'));
        }

        abort(404);
    }
}

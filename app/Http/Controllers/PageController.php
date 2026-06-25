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
        $page = Page::where('slug', $slug)->firstOrFail();
        return view('pages.show', compact('page'));
    }
}

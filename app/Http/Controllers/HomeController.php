<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Show the application landing page.
     */
    public function index(): View
    {
        // Get the latest 3 published articles for the landing page blog section
        $recentPosts = Content::where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        return view('home', compact('recentPosts'));
    }
}

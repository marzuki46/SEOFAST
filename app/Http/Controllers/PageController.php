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
            return app()->make(HomeController::class)->index();
        }
        return $this->renderPage($page);
    }

    public function show($slug)
    {
        // Fast bail — bots scanning for files or exploits
        if (preg_match('/\.\w{2,4}$/', $slug) || preg_match('/^(wp-|\.env|config|admin|xmlrpc|phpmyadmin|_profiler)/i', $slug)) {
            abort(404);
        }

        $page = Page::where('slug', $slug)->first();

        if ($page) {
            return $this->renderPage($page);
        }

        // Only check for child pages if the slug looks like a legitimate path segment
        if (preg_match('/^[a-z0-9\/-]+$/', $slug)) {
            $childPages = Page::where('slug', 'like', $slug . '/%')->orderBy('created_at', 'desc')->paginate(12);

            if ($childPages->count() > 0) {
                return view('pages.archive', compact('slug', 'childPages'));
            }
        }

        abort(404);
    }

    protected function renderPage(Page $page)
    {
        $template = $page->template ?? 'default';

        if (!view()->exists('pages.templates.' . $template)) {
            $template = 'default';
        }

        return view('pages.show', compact('page', 'template'));
    }
}

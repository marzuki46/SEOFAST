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

        // Legacy/duplicate slugs → 301 ke URL kanonik (lihat config/seo.php).
        $aliases = config('seo.page_aliases', []);
        if (isset($aliases[$slug])) {
            $target = $aliases[$slug];
            if (app()->getLocale() === 'en' && !str_starts_with($target, '/en')) {
                $target = '/en' . $target;
            }
            return redirect($target, 301);
        }

        // Redirect legacy id/jasa to /jasa
        if ($slug === 'id/jasa' || str_starts_with($slug, 'id/jasa/')) {
            $target = preg_replace('#^id/jasa#', 'jasa', $slug);
            return redirect('/' . $target, 301);
        }

        $page = Page::where('slug', $slug)->first();

        if ($page) {
            // The homepage page is served at / (or /en); serving it via its slug
            // creates duplicate content — 301 to the canonical URL.
            if ($page->is_homepage) {
                return redirect(app()->getLocale() === 'en' ? url('/en') : url('/'), 301);
            }

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

        $standalone = ['juki-landing', 'juki-about', 'juki-services', 'juki-service-detail'];
        if (in_array($template, $standalone, true)) {
            if ($template === 'juki-service-detail' && trim($page->slug, '/') === 'jasa/website') {
                return view('pages.templates.juki-website-service', compact('page'));
            }

            if ($template === 'juki-service-detail') {
                return view('pages.templates.juki-service-modern', compact('page'));
            }

            return view('pages.templates.' . $template, compact('page'));
        }

        return view('pages.show', compact('page', 'template'));
    }
}

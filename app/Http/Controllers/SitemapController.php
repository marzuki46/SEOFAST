<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\SiloBlueprint;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Generate dynamic sitemap.xml — only published content
     */
    public function index(): Response
    {
        $contents = Content::withoutGlobalScopes()
            ->where('status', 'published')
            ->orderByDesc('crawl_priority_score')
            ->get(['slug', 'published_at', 'crawl_priority_score', 'hierarchy_level']);

        $categories = SiloBlueprint::withoutGlobalScopes()->get(['slug', 'updated_at']);
        $products = \App\Models\Product::withoutGlobalScopes()->where('is_active', true)->get(['slug', 'updated_at']);
        $pages = \App\Models\Page::withoutGlobalScopes()->where('is_published', true)->get(['slug', 'updated_at']);

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        // Homepage
        $xml .= '  <url>' . PHP_EOL;
        $xml .= '    <loc>' . url('/') . '</loc>' . PHP_EOL;
        $xml .= '    <priority>1.0</priority>' . PHP_EOL;
        $xml .= '    <changefreq>daily</changefreq>' . PHP_EOL;
        $xml .= '  </url>' . PHP_EOL;

        // Blog Index
        $xml .= '  <url>' . PHP_EOL;
        $xml .= '    <loc>' . url('/blog') . '</loc>' . PHP_EOL;
        $xml .= '    <priority>0.9</priority>' . PHP_EOL;
        $xml .= '    <changefreq>daily</changefreq>' . PHP_EOL;
        $xml .= '  </url>' . PHP_EOL;

        // Categories
        foreach ($categories as $category) {
            $xml .= '  <url>' . PHP_EOL;
            $xml .= '    <loc>' . url('/blog/category/' . $category->slug) . '</loc>' . PHP_EOL;
            $xml .= '    <lastmod>' . ($category->updated_at?->toAtomString() ?? now()->toAtomString()) . '</lastmod>' . PHP_EOL;
            $xml .= '    <priority>0.8</priority>' . PHP_EOL;
            $xml .= '    <changefreq>weekly</changefreq>' . PHP_EOL;
            $xml .= '  </url>' . PHP_EOL;
        }

        // Products
        foreach ($products as $product) {
            $xml .= '  <url>' . PHP_EOL;
            $xml .= '    <loc>' . url('/products/' . $product->slug) . '</loc>' . PHP_EOL;
            $xml .= '    <lastmod>' . ($product->updated_at?->toAtomString() ?? now()->toAtomString()) . '</lastmod>' . PHP_EOL;
            $xml .= '    <priority>0.9</priority>' . PHP_EOL;
            $xml .= '    <changefreq>weekly</changefreq>' . PHP_EOL;
            $xml .= '  </url>' . PHP_EOL;
        }

        // Pages
        foreach ($pages as $page) {
            $xml .= '  <url>' . PHP_EOL;
            $xml .= '    <loc>' . url('/' . $page->slug) . '</loc>' . PHP_EOL;
            $xml .= '    <lastmod>' . ($page->updated_at?->toAtomString() ?? now()->toAtomString()) . '</lastmod>' . PHP_EOL;
            $xml .= '    <priority>0.7</priority>' . PHP_EOL;
            $xml .= '    <changefreq>monthly</changefreq>' . PHP_EOL;
            $xml .= '  </url>' . PHP_EOL;
        }

        // Articles
        foreach ($contents as $content) {
            $priority = max(0.1, min(1.0, (float) $content->crawl_priority_score));
            $changefreq = match(true) {
                $priority >= 0.8 => 'weekly',
                $priority >= 0.5 => 'monthly',
                default => 'yearly',
            };

            $xml .= '  <url>' . PHP_EOL;
            $xml .= '    <loc>' . url('/blog/' . $content->slug) . '</loc>' . PHP_EOL;
            $xml .= '    <lastmod>' . ($content->published_at?->toAtomString() ?? now()->toAtomString()) . '</lastmod>' . PHP_EOL;
            $xml .= '    <priority>' . number_format($priority, 1) . '</priority>' . PHP_EOL;
            $xml .= '    <changefreq>' . $changefreq . '</changefreq>' . PHP_EOL;
            $xml .= '  </url>' . PHP_EOL;
        }

        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }

    /**
     * Dynamic robots.txt
     */
    public function robots(): Response
    {
        // Read from database settings if available, otherwise use default
        $customRobots = \App\Models\SystemSetting::get('robots_txt_content');

        if ($customRobots) {
            return response($customRobots, 200, ['Content-Type' => 'text/plain']);
        }

        // Default robots.txt
        $content  = "User-agent: *\n";
        $content .= "Allow: /\n";
        $content .= "Disallow: /admin/\n";
        $content .= "Disallow: /login\n";
        $content .= "Disallow: /dashboard\n";
        $content .= "Disallow: /buyer/\n";
        $content .= "Disallow: /ghost/\n\n";
        $content .= "Sitemap: " . url('/sitemap.xml') . "\n";

        return response($content, 200, ['Content-Type' => 'text/plain']);
    }

    /**
     * Ghost Publish — Blueprint URL with noindex placeholder
     */
    public function ghost(string $slug): \Illuminate\View\View|Response
    {
        $content = Content::withoutGlobalScopes()
            ->where('slug', $slug)
            ->first();

        if (!$content) {
            abort(404);
        }

        // If published, redirect to actual blog
        if ($content->status === 'published') {
            return redirect('/blog/' . $slug, 301);
        }

        // Ghost publish placeholder — noindex
        return view('ghost', compact('content'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\SiloBlueprint;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    private const PER_PAGE = 1000;

    /**
     * Sitemap index — references sub-sitemaps
     */
    public function index(): Response
    {
        $multiLang = \App\Models\SystemSetting::get('enable_auto_translate_en', '0') === '1';
        $nowAtom = now()->toAtomString();

        $totalPosts = Content::withoutGlobalScopes()
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->count();

        $totalPages = max(1, (int) ceil($totalPosts / self::PER_PAGE));

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        $xml .= '  <sitemap>' . PHP_EOL;
        $xml .= '    <loc>' . url('/sitemap-static.xml') . '</loc>' . PHP_EOL;
        $xml .= '    <lastmod>' . $nowAtom . '</lastmod>' . PHP_EOL;
        $xml .= '  </sitemap>' . PHP_EOL;

        for ($i = 1; $i <= $totalPages; $i++) {
            $xml .= '  <sitemap>' . PHP_EOL;
            $xml .= '    <loc>' . url('/sitemap-posts-' . $i . '.xml') . '</loc>' . PHP_EOL;
            $xml .= '    <lastmod>' . $nowAtom . '</lastmod>' . PHP_EOL;
            $xml .= '  </sitemap>' . PHP_EOL;
        }

        if ($multiLang) {
            $xml .= '  <sitemap>' . PHP_EOL;
            $xml .= '    <loc>' . url('/sitemap-en-static.xml') . '</loc>' . PHP_EOL;
            $xml .= '    <lastmod>' . $nowAtom . '</lastmod>' . PHP_EOL;
            $xml .= '  </sitemap>' . PHP_EOL;

            for ($i = 1; $i <= $totalPages; $i++) {
                $xml .= '  <sitemap>' . PHP_EOL;
                $xml .= '    <loc>' . url('/sitemap-en-posts-' . $i . '.xml') . '</loc>' . PHP_EOL;
                $xml .= '    <lastmod>' . $nowAtom . '</lastmod>' . PHP_EOL;
                $xml .= '  </sitemap>' . PHP_EOL;
            }
        }

        $xml .= '</sitemapindex>';

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }

    /**
     * Static sitemap — homepage, blog index, categories, pages, products
     */
    public function staticSitemap(): Response
    {
        $categories = SiloBlueprint::withoutGlobalScopes()->get(['silo_name', 'updated_at']);
        $products = \App\Models\Product::withoutGlobalScopes()->where('is_active', true)->get(['slug', 'updated_at']);
        $pages = \App\Models\Page::withoutGlobalScopes()->where('is_published', true)->get(['slug', 'updated_at']);

        $nowAtom = now()->toAtomString();
        $blogPrefix = \App\Models\SystemSetting::get('permalink_blog', 'blog');
        $productPrefix = \App\Models\SystemSetting::get('permalink_product', 'produk');
        if ($productPrefix === '0') $productPrefix = 'produk';
        $lastAtom = now()->toAtomString();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        // Homepage
        $xml .= '  <url><loc>' . url('/') . '</loc><lastmod>' . $nowAtom . '</lastmod><priority>1.0</priority><changefreq>daily</changefreq></url>' . PHP_EOL;

        // Blog Index
        $xml .= '  <url><loc>' . url('/' . $blogPrefix) . '</loc><lastmod>' . $nowAtom . '</lastmod><priority>0.9</priority><changefreq>daily</changefreq></url>' . PHP_EOL;

        // Categories
        foreach ($categories as $category) {
            $catSlug = $category->slug;
            $catLastmod = $category->updated_at?->toAtomString() ?? $lastAtom;
            $xml .= '  <url><loc>' . url('/' . $blogPrefix . '/category/' . $catSlug) . '</loc><lastmod>' . $catLastmod . '</lastmod><priority>0.8</priority><changefreq>weekly</changefreq></url>' . PHP_EOL;
        }

        // Products
        foreach ($products as $product) {
            $prodSlug = $product->slug;
            $prodLastmod = $product->updated_at?->toAtomString() ?? $lastAtom;
            $xml .= '  <url><loc>' . url('/' . $productPrefix . '/' . $prodSlug) . '</loc><lastmod>' . $prodLastmod . '</lastmod><priority>0.9</priority><changefreq>weekly</changefreq></url>' . PHP_EOL;
        }

        // Pages
        foreach ($pages as $page) {
            $pageSlug = $page->slug;
            $pageLastmod = $page->updated_at?->toAtomString() ?? $lastAtom;
            $xml .= '  <url><loc>' . url('/' . $pageSlug) . '</loc><lastmod>' . $pageLastmod . '</lastmod><priority>0.7</priority><changefreq>monthly</changefreq></url>' . PHP_EOL;
        }

        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }

    /**
     * Paginated blog posts sitemap
     */
    public function postsSitemap(int $page = 1): Response
    {
        $contents = Content::withoutGlobalScopes()
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->orderByDesc('crawl_priority_score')
            ->paginate(self::PER_PAGE, ['slug', 'published_at', 'crawl_priority_score', 'hierarchy_level'], 'page', $page);

        $blogPrefix = \App\Models\SystemSetting::get('permalink_blog', 'blog');
        $lastAtom = now()->toAtomString();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        foreach ($contents as $content) {
            $priority = max(0.1, min(1.0, (float) $content->crawl_priority_score));
            $changefreq = match(true) {
                $priority >= 0.8 => 'weekly',
                $priority >= 0.5 => 'monthly',
                default => 'yearly',
            };
            $articleLastmod = $content->published_at?->toAtomString() ?? $lastAtom;
            $xml .= '  <url><loc>' . url('/' . $blogPrefix . '/' . $content->slug) . '</loc><lastmod>' . $articleLastmod . '</lastmod><priority>' . number_format($priority, 1) . '</priority><changefreq>' . $changefreq . '</changefreq></url>' . PHP_EOL;
        }

        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }

    /**
     * Static sitemap for English locale
     */
    public function staticSitemapEn(): Response
    {
        $categories = SiloBlueprint::withoutGlobalScopes()->get(['silo_name', 'updated_at']);
        $pages = \App\Models\Page::withoutGlobalScopes()->where('is_published', true)->get(['slug', 'updated_at']);

        $nowAtom = now()->toAtomString();
        $blogPrefix = \App\Models\SystemSetting::get('permalink_blog', 'blog');
        $productPrefix = \App\Models\SystemSetting::get('permalink_product', 'produk');
        if ($productPrefix === '0') $productPrefix = 'produk';
        $lastAtom = now()->toAtomString();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        $xml .= '  <url><loc>' . url('/en') . '</loc><lastmod>' . $nowAtom . '</lastmod><priority>1.0</priority><changefreq>daily</changefreq></url>' . PHP_EOL;
        $xml .= '  <url><loc>' . url('/en/' . $blogPrefix) . '</loc><lastmod>' . $nowAtom . '</lastmod><priority>0.9</priority><changefreq>daily</changefreq></url>' . PHP_EOL;

        foreach ($categories as $category) {
            $catSlug = $category->slug;
            $catLastmod = $category->updated_at?->toAtomString() ?? $lastAtom;
            $xml .= '  <url><loc>' . url('/en/' . $blogPrefix . '/category/' . $catSlug) . '</loc><lastmod>' . $catLastmod . '</lastmod><priority>0.8</priority><changefreq>weekly</changefreq></url>' . PHP_EOL;
        }

        $products = \App\Models\Product::withoutGlobalScopes()->where('is_active', true)->get(['slug', 'updated_at']);
        foreach ($products as $product) {
            $prodSlug = $product->slug;
            $prodLastmod = $product->updated_at?->toAtomString() ?? $lastAtom;
            $xml .= '  <url><loc>' . url('/en/' . $productPrefix . '/' . $prodSlug) . '</loc><lastmod>' . $prodLastmod . '</lastmod><priority>0.9</priority><changefreq>weekly</changefreq></url>' . PHP_EOL;
        }

        foreach ($pages as $page) {
            $pageSlug = $page->slug;
            $pageLastmod = $page->updated_at?->toAtomString() ?? $lastAtom;
            $xml .= '  <url><loc>' . url('/en/' . $pageSlug) . '</loc><lastmod>' . $pageLastmod . '</lastmod><priority>0.7</priority><changefreq>monthly</changefreq></url>' . PHP_EOL;
        }

        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }

    /**
     * Paginated English blog posts sitemap
     */
    public function postsSitemapEn(int $page = 1): Response
    {
        $contents = Content::withoutGlobalScopes()
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->orderByDesc('crawl_priority_score')
            ->paginate(self::PER_PAGE, ['slug', 'published_at', 'crawl_priority_score', 'hierarchy_level'], 'page', $page);

        $blogPrefix = \App\Models\SystemSetting::get('permalink_blog', 'blog');
        $lastAtom = now()->toAtomString();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        foreach ($contents as $content) {
            $priority = max(0.1, min(1.0, (float) $content->crawl_priority_score));
            $changefreq = match(true) {
                $priority >= 0.8 => 'weekly',
                $priority >= 0.5 => 'monthly',
                default => 'yearly',
            };
            $articleLastmod = $content->published_at?->toAtomString() ?? $lastAtom;
            $xml .= '  <url><loc>' . url('/en/' . $blogPrefix . '/' . $content->slug) . '</loc><lastmod>' . $articleLastmod . '</lastmod><priority>' . number_format($priority, 1) . '</priority><changefreq>' . $changefreq . '</changefreq></url>' . PHP_EOL;
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
        $content .= "Disallow: /master/adminis-trator\n";
        $content .= "Disallow: /admin/dashboard\n";
        $content .= "Disallow: /buyer/\n";
        $content .= "Disallow: /g/\n\n";
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

        // Blueprint without actual content → 404 to save crawl budget
        if (empty(trim(strip_tags($content->body_raw ?? '')))) {
            abort(404);
        }

        // Ghost publish placeholder — noindex
        return view('ghost', compact('content'));
    }
}

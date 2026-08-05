<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Page;
use App\Models\Product;
use App\Models\SiloBlueprint;
use App\Models\SystemSetting;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    private const PER_PAGE = 1000;

    private function multiLangEnabled(): bool
    {
        return (bool) SystemSetting::get('enable_auto_translate_en', false);
    }

    private function blogPrefix(): string
    {
        return (string) SystemSetting::get('permalink_blog', 'blog');
    }

    private function productPrefix(): string
    {
        $prefix = (string) SystemSetting::get('permalink_product', 'produk');
        return ($prefix === '' || $prefix === '0') ? 'produk' : $prefix;
    }

    private function homepageLastmod(): string
    {
        $lastmod = Page::withoutGlobalScopes()->where('is_homepage', true)->value('updated_at');
        return $lastmod?->toAtomString() ?? now()->toAtomString();
    }

    private function blogIndexLastmod(): string
    {
        $lastmod = Content::withoutGlobalScopes()
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->orderByDesc('updated_at')
            ->value('updated_at');
        return $lastmod?->toAtomString() ?? now()->toAtomString();
    }

    private function postLastmod(Content $content): string
    {
        $last = $content->updated_at ?? $content->published_at;
        if ($content->last_partial_update_at && $content->last_partial_update_at > $last) {
            $last = $content->last_partial_update_at;
        }
        return $last?->toAtomString() ?? now()->toAtomString();
    }

    private function postPriority(Content $content): float
    {
        if ((float) $content->crawl_priority_score > 0) {
            $priority = (float) $content->crawl_priority_score;
        } else {
            $days = now()->diffInDays($content->published_at ?? now(), false);
            $priority = match (true) {
                $days <= 30  => 0.9,
                $days <= 90  => 0.7,
                $days <= 180 => 0.6,
                default      => 0.4,
            };
            if ($content->hierarchy_level === 'pillar') $priority += 0.1;
            if ((int) $content->search_volume > 0) $priority += 0.05;
            if ($content->gsc_coverage_state === 'Submitted and indexed') $priority += 0.05;
            $position = (int) $content->current_serp_position;
            if ($position > 0 && $position <= 3) $priority += 0.05;
        }
        return max(0.1, min(1.0, round($priority, 1)));
    }

    private function postChangefreq(float $priority): string
    {
        return match (true) {
            $priority >= 0.8 => 'weekly',
            $priority >= 0.5 => 'monthly',
            default          => 'yearly',
        };
    }

    private function hreflangLinks(string $idUrl, string $enUrl): string
    {
        if (!$this->multiLangEnabled()) return '';

        return '    <xhtml:link rel="alternate" hreflang="id" href="' . e($idUrl) . '"/>' . PHP_EOL
             . '    <xhtml:link rel="alternate" hreflang="en" href="' . e($enUrl) . '"/>' . PHP_EOL
             . '    <xhtml:link rel="alternate" hreflang="x-default" href="' . e($idUrl) . '"/>' . PHP_EOL;
    }

    private function urlEntry(string $loc, string $lastmod, string $priority, string $changefreq, string $extra = ''): string
    {
        return '  <url>' . PHP_EOL
             . '    <loc>' . e($loc) . '</loc>' . PHP_EOL
             . $extra
             . '    <lastmod>' . $lastmod . '</lastmod>' . PHP_EOL
             . '    <priority>' . $priority . '</priority>' . PHP_EOL
             . '    <changefreq>' . $changefreq . '</changefreq>' . PHP_EOL
             . '  </url>' . PHP_EOL;
    }

    private function xmlHeader(): string
    {
        return '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL
             . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . PHP_EOL;
    }

    /**
     * Sitemap index — references sub-sitemaps
     */
    public function index(): Response
    {
        $multiLang = $this->multiLangEnabled();
        $lastmod = $this->blogIndexLastmod();

        $totalPosts = Content::withoutGlobalScopes()
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->count();

        $totalPages = max(1, (int) ceil($totalPosts / self::PER_PAGE));

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        $xml .= '  <sitemap>' . PHP_EOL;
        $xml .= '    <loc>' . url('/sitemap-static.xml') . '</loc>' . PHP_EOL;
        $xml .= '    <lastmod>' . $lastmod . '</lastmod>' . PHP_EOL;
        $xml .= '  </sitemap>' . PHP_EOL;

        for ($i = 1; $i <= $totalPages; $i++) {
            $xml .= '  <sitemap>' . PHP_EOL;
            $xml .= '    <loc>' . url('/sitemap-posts-' . $i . '.xml') . '</loc>' . PHP_EOL;
            $xml .= '    <lastmod>' . $lastmod . '</lastmod>' . PHP_EOL;
            $xml .= '  </sitemap>' . PHP_EOL;
        }

        if ($multiLang) {
            $xml .= '  <sitemap>' . PHP_EOL;
            $xml .= '    <loc>' . url('/sitemap-en-static.xml') . '</loc>' . PHP_EOL;
            $xml .= '    <lastmod>' . $lastmod . '</lastmod>' . PHP_EOL;
            $xml .= '  </sitemap>' . PHP_EOL;

            for ($i = 1; $i <= $totalPages; $i++) {
                $xml .= '  <sitemap>' . PHP_EOL;
                $xml .= '    <loc>' . url('/sitemap-en-posts-' . $i . '.xml') . '</loc>' . PHP_EOL;
                $xml .= '    <lastmod>' . $lastmod . '</lastmod>' . PHP_EOL;
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
        $seen = [];
        $xml = $this->xmlHeader();

        $blogPrefix = $this->blogPrefix();
        $productPrefix = $this->productPrefix();
        $homeLastmod = $this->homepageLastmod();
        $blogLastmod = $this->blogIndexLastmod();

        // Homepage
        $xml .= $this->urlEntry(url('/'), $homeLastmod, '1.0', 'daily',
            $this->hreflangLinks(url('/'), url('/en')));

        // Contact
        $xml .= $this->urlEntry(url('/contact'), $homeLastmod, '0.7', 'monthly');

        // Blog Index
        $xml .= $this->urlEntry(url('/' . $blogPrefix), $blogLastmod, '0.9', 'daily',
            $this->hreflangLinks(url('/' . $blogPrefix), url('/en/' . $blogPrefix)));

        // Categories
        foreach (SiloBlueprint::withoutGlobalScopes()->get(['silo_name', 'updated_at']) as $category) {
            $catLastmod = $category->updated_at?->toAtomString() ?? $blogLastmod;
            $xml .= $this->urlEntry(url('/' . $blogPrefix . '/category/' . $category->slug), $catLastmod, '0.8', 'weekly',
                $this->hreflangLinks(
                    url('/' . $blogPrefix . '/category/' . $category->slug),
                    url('/en/' . $blogPrefix . '/category/' . $category->slug)
                ));
        }

        // Product Catalog Index
        $xml .= $this->urlEntry(url('/' . $productPrefix), $blogLastmod, '0.9', 'daily',
            $this->hreflangLinks(url('/' . $productPrefix), url('/en/' . $productPrefix)));

        // Products
        foreach (Product::withoutGlobalScopes()->where('is_active', true)->get(['slug', 'updated_at']) as $product) {
            $prodLastmod = $product->updated_at?->toAtomString() ?? $blogLastmod;
            $xml .= $this->urlEntry(url('/' . $productPrefix . '/' . $product->slug), $prodLastmod, '0.9', 'weekly',
                $this->hreflangLinks(
                    url('/' . $productPrefix . '/' . $product->slug),
                    url('/en/' . $productPrefix . '/' . $product->slug)
                ));
        }

        // Pages (skip homepage page + slugs shadowed by real routes)
        foreach (Page::withoutGlobalScopes()->where('is_published', true)->get(['slug', 'updated_at']) as $page) {
            if ($page->is_homepage) continue;
            if (in_array($page->slug, ['contact', 'home'], true)) continue;

            $loc = url('/' . $page->slug);
            if (isset($seen[$loc])) continue;
            $seen[$loc] = true;

            $pageLastmod = $page->updated_at?->toAtomString() ?? $blogLastmod;
            $xml .= $this->urlEntry($loc, $pageLastmod, '0.7', 'monthly');
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
            ->orderByDesc('updated_at')
            ->paginate(self::PER_PAGE, ['slug', 'published_at', 'updated_at', 'last_partial_update_at', 'crawl_priority_score', 'hierarchy_level', 'search_volume', 'gsc_coverage_state', 'current_serp_position'], 'page', $page);

        $blogPrefix = $this->blogPrefix();
        $xml = $this->xmlHeader();

        foreach ($contents as $content) {
            $priority = $this->postPriority($content);
            $loc = url('/' . $blogPrefix . '/' . $content->slug);
            $xml .= $this->urlEntry($loc, $this->postLastmod($content), number_format($priority, 1), $this->postChangefreq($priority),
                $this->hreflangLinks($loc, url('/en/' . $blogPrefix . '/' . $content->slug)));
        }

        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }

    /**
     * Static sitemap for English locale
     */
    public function staticSitemapEn(): Response
    {
        $seen = [];
        $xml = $this->xmlHeader();

        $blogPrefix = $this->blogPrefix();
        $productPrefix = $this->productPrefix();
        $homeLastmod = $this->homepageLastmod();
        $blogLastmod = $this->blogIndexLastmod();

        // Homepage
        $xml .= $this->urlEntry(url('/en'), $homeLastmod, '1.0', 'daily',
            $this->hreflangLinks(url('/'), url('/en')));

        // Contact
        $xml .= $this->urlEntry(url('/en/contact'), $homeLastmod, '0.7', 'monthly');

        // Blog Index
        $xml .= $this->urlEntry(url('/en/' . $blogPrefix), $blogLastmod, '0.9', 'daily',
            $this->hreflangLinks(url('/' . $blogPrefix), url('/en/' . $blogPrefix)));

        // Categories
        foreach (SiloBlueprint::withoutGlobalScopes()->get(['silo_name', 'updated_at']) as $category) {
            $catLastmod = $category->updated_at?->toAtomString() ?? $blogLastmod;
            $xml .= $this->urlEntry(url('/en/' . $blogPrefix . '/category/' . $category->slug), $catLastmod, '0.8', 'weekly',
                $this->hreflangLinks(
                    url('/' . $blogPrefix . '/category/' . $category->slug),
                    url('/en/' . $blogPrefix . '/category/' . $category->slug)
                ));
        }

        // Product Catalog Index
        $xml .= $this->urlEntry(url('/en/' . $productPrefix), $blogLastmod, '0.9', 'daily',
            $this->hreflangLinks(url('/' . $productPrefix), url('/en/' . $productPrefix)));

        // Products
        foreach (Product::withoutGlobalScopes()->where('is_active', true)->get(['slug', 'updated_at']) as $product) {
            $prodLastmod = $product->updated_at?->toAtomString() ?? $blogLastmod;
            $xml .= $this->urlEntry(url('/en/' . $productPrefix . '/' . $product->slug), $prodLastmod, '0.9', 'weekly',
                $this->hreflangLinks(
                    url('/' . $productPrefix . '/' . $product->slug),
                    url('/en/' . $productPrefix . '/' . $product->slug)
                ));
        }

        // Pages (skip homepage page + slugs shadowed by real routes)
        foreach (Page::withoutGlobalScopes()->where('is_published', true)->get(['slug', 'updated_at']) as $page) {
            if ($page->is_homepage) continue;
            if (in_array($page->slug, ['contact', 'home'], true)) continue;

            $loc = url('/en/' . $page->slug);
            if (isset($seen[$loc])) continue;
            $seen[$loc] = true;

            $pageLastmod = $page->updated_at?->toAtomString() ?? $blogLastmod;
            $xml .= $this->urlEntry($loc, $pageLastmod, '0.7', 'monthly');
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
            ->orderByDesc('updated_at')
            ->paginate(self::PER_PAGE, ['slug', 'published_at', 'updated_at', 'last_partial_update_at', 'crawl_priority_score', 'hierarchy_level', 'search_volume', 'gsc_coverage_state', 'current_serp_position'], 'page', $page);

        $blogPrefix = $this->blogPrefix();
        $xml = $this->xmlHeader();

        foreach ($contents as $content) {
            $priority = $this->postPriority($content);
            $loc = url('/en/' . $blogPrefix . '/' . $content->slug);
            $xml .= $this->urlEntry($loc, $this->postLastmod($content), number_format($priority, 1), $this->postChangefreq($priority),
                $this->hreflangLinks(url('/' . $blogPrefix . '/' . $content->slug), $loc));
        }

        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }

    /**
     * Dynamic robots.txt
     */
    public function robots(): Response
    {
        $customRobots = SystemSetting::get('robots_txt_content');

        if ($customRobots) {
            $content = $customRobots;
            if (!str_contains($content, 'Sitemap:')) {
                $content = rtrim($content) . PHP_EOL . PHP_EOL . 'Sitemap: ' . url('/sitemap.xml') . PHP_EOL;
            }
            return response($content, 200, ['Content-Type' => 'text/plain']);
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
        $content = Content::withoutGlobalScopes()->whereSlug($slug)->first();

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
        return response()->view('ghost', compact('content'))
            ->header('X-Robots-Tag', 'noindex, nofollow');
    }
}

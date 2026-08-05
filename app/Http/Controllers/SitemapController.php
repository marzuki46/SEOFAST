<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Page;
use App\Models\Product;
use App\Models\Redirect;
use App\Models\SiloBlueprint;
use App\Models\SystemSetting;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    private const PER_PAGE = 1000;

    /**
     * Durasi cache sitemap di origin (detik).
     * Invalidasi otomatis via flushCache() setiap ada publish/update/delete.
     */
    private const CACHE_TTL = 3600;

    /**
     * Naikkan versi cache sitemap. Panggil setelah konten berubah
     * (publish, update, delete) agar sitemap tidak menampilkan data basi.
     *
     * Pakai get+put (bukan increment) agar bekerja di semua cache store,
     * termasuk database store yang tidak menjamin persistensi key baru.
     */
    public static function flushCache(): void
    {
        $version = (int) Cache::get('seo.sitemap.version', 0) + 1;
        Cache::put('seo.sitemap.version', $version, self::CACHE_TTL * 24);
    }

    private function cacheVersion(): int
    {
        return (int) Cache::get('seo.sitemap.version', 0);
    }

    private function cacheKey(string $key): string
    {
        return 'sitemap.v' . $this->cacheVersion() . '.' . $key;
    }

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

    /**
     * Slug post duplikat yang dibuat mesin (suffix -### / -1 / -2) saat
     * post induk dengan slug dasar yang sama sudah ada — di-skip dari
     * sitemap agar crawl budget tidak terbuang.
     *
     * Heuristik konservatif: hanya slug yang (a) induk "base"-nya benar-benar
     * ada, DAN (b) suffix-nya cocok pola penomoran mesin (3 digit acak dari
     * ContentController::store, atau penamaan lama -1/-2). Slug legit yang
     * berakhiran tahun seperti "checklist-seo-2026" TIDAK ikut terbuang.
     */
    private function duplicateGeneratedSlugs(): array
    {
        $published = Content::withoutGlobalScopes()
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->pluck('slug');

        $slugSet = [];
        foreach ($published as $slug) {
            $slugSet[$slug] = true;
        }

        $dupes = [];
        foreach ($published as $slug) {
            $base = null;
            if (preg_match('/^(.*)-(\d{3})$/', $slug, $m)) {
                $base = $m[1]; // suffix mesin ContentController::store: rand(100,999)
            } elseif (preg_match('/^(.*)-(\d{1,2})$/', $slug, $m)) {
                $base = $m[1]; // penomoran sekuensial SiloBlueprintController: -1, -2, dst
            }
            if ($base === null || !isset($slugSet[$base])) continue;
            $dupes[$slug] = true;
        }

        return array_keys($dupes);
    }

    /**
     * Slug halaman yang tidak boleh masuk sitemap:
     *  - terdaftar sebagai alias/redirect (config/seo.php)
     *  - punya baris redirect aktif di tabel redirects (old_url = /slug)
     */
    private function excludedPageSlugs(): array
    {
        $slugs = array_keys(config('seo.page_aliases', []));

        $redirectPaths = Redirect::active()->pluck('old_url')->map(fn ($url) => '/' . ltrim($url, '/'))->all();
        foreach ($redirectPaths as $path) {
            $slugs[] = trim(str_replace('\\', '/', $path), '/');
        }

        return array_values(array_unique($slugs));
    }

    /**
     * Filter dasar post yang benar-benar punya isi — identik dengan
     * BlogController, sehingga sitemap tidak memuat blueprint kosong
     * (soft-404 / pemborosan crawl budget).
     *
     * $requireEn = true → hanya post dengan body bahasa Inggris terisi.
     */
    private function applyPostBodyFilter($query, bool $requireEn = false)
    {
        $query = $query
            ->whereNotNull('body_raw')
            ->where('body_raw', '!=', '{"id":""}')
            ->where('body_raw', '!=', '{"en":""}');

        return $query->where(function ($q) use ($requireEn) {
            if ($requireEn) {
                $q->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(body_raw, '$.en')) != ''");
            } else {
                $q->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(body_raw, '$.id')) != ''")
                  ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(body_raw, '$.en')) != ''");
            }
        });
    }

    private function postChangefreq(float $priority): string
    {
        return match (true) {
            $priority >= 0.8 => 'weekly',
            $priority >= 0.5 => 'monthly',
            default          => 'yearly',
        };
    }

    private function hreflangLinks(string $idUrl, string $enUrl, bool $includeEn = true): string
    {
        if (!$this->multiLangEnabled()) return '';

        $xml = '    <xhtml:link rel="alternate" hreflang="id" href="' . e($idUrl) . '"/>' . PHP_EOL;
        if ($includeEn) {
            $xml .= '    <xhtml:link rel="alternate" hreflang="en" href="' . e($enUrl) . '"/>' . PHP_EOL;
        }
        $xml .= '    <xhtml:link rel="alternate" hreflang="x-default" href="' . e($idUrl) . '"/>' . PHP_EOL;

        return $xml;
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
        // Cache menyimpan STRING XML (bukan objek Response) agar aman di-unserialize
        // oleh semua cache driver (file/redis/database).
        $xml = Cache::remember($this->cacheKey('index'), self::CACHE_TTL, function () {
            $multiLang = $this->multiLangEnabled();
            $lastmod = $this->blogIndexLastmod();

            // Hitung dengan filter body yang sama seperti postsSitemap,
            // agar tidak ada halaman sitemap-posts-N.xml yang kosong total.
            $totalPosts = $this->applyPostBodyFilter(
                Content::withoutGlobalScopes()
                    ->where('status', 'published')
                    ->where('published_at', '<=', now()),
                false
            )->count();

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

            return $xml;
        });

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }

    /**
     * Static sitemap — homepage, blog index, categories, pages, products
     */
    public function staticSitemap(): Response
    {
        $xml = Cache::remember($this->cacheKey('static.id'), self::CACHE_TTL, function () {
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
            $excludedSlugs = $this->excludedPageSlugs();
            foreach (Page::withoutGlobalScopes()->where('is_published', true)->get(['slug', 'updated_at']) as $page) {
                if ($page->is_homepage) continue;
                if (in_array($page->slug, ['contact', 'home'], true)) continue;
                if (in_array($page->slug, $excludedSlugs, true)) continue;

                $loc = url('/' . $page->slug);
                if (isset($seen[$loc])) continue;
                $seen[$loc] = true;

                $pageLastmod = $page->updated_at?->toAtomString() ?? $blogLastmod;
                $xml .= $this->urlEntry($loc, $pageLastmod, '0.7', 'monthly');
            }

            $xml .= '</urlset>';

            return $xml;
        });

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }

    /**
     * Paginated blog posts sitemap
     */
    public function postsSitemap(int $page = 1): Response
    {
        $xml = Cache::remember($this->cacheKey('posts.id.' . $page), self::CACHE_TTL, function () use ($page) {
            $contents = $this->applyPostBodyFilter(
                Content::withoutGlobalScopes()
                    ->where('status', 'published')
                    ->where('published_at', '<=', now())
                    ->orderByDesc('updated_at'),
                false
            )->paginate(self::PER_PAGE, ['slug', 'body_raw', 'published_at', 'updated_at', 'last_partial_update_at', 'crawl_priority_score', 'hierarchy_level', 'search_volume', 'gsc_coverage_state', 'current_serp_position'], 'page', $page);

            $blogPrefix = $this->blogPrefix();
            $xml = $this->xmlHeader();
            $dupeSlugs = $this->duplicateGeneratedSlugs();

            foreach ($contents as $content) {
                if (in_array($content->slug, $dupeSlugs, true)) continue;

                $priority = $this->postPriority($content);
                $loc = url('/' . $blogPrefix . '/' . $content->slug);
                $xml .= $this->urlEntry($loc, $this->postLastmod($content), number_format($priority, 1), $this->postChangefreq($priority),
                    $this->hreflangLinks($loc, url('/en/' . $blogPrefix . '/' . $content->slug), $content->hasEnglishContent()));
            }

            $xml .= '</urlset>';

            return $xml;
        });

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }

    /**
     * Static sitemap for English locale
     */
    public function staticSitemapEn(): Response
    {
        $xml = Cache::remember($this->cacheKey('static.en'), self::CACHE_TTL, function () {
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
            $excludedSlugs = $this->excludedPageSlugs();
            foreach (Page::withoutGlobalScopes()->where('is_published', true)->get(['slug', 'updated_at']) as $page) {
                if ($page->is_homepage) continue;
                if (in_array($page->slug, ['contact', 'home'], true)) continue;
                if (in_array($page->slug, $excludedSlugs, true)) continue;

                $loc = url('/en/' . $page->slug);
                if (isset($seen[$loc])) continue;
                $seen[$loc] = true;

                $pageLastmod = $page->updated_at?->toAtomString() ?? $blogLastmod;
                $xml .= $this->urlEntry($loc, $pageLastmod, '0.7', 'monthly');
            }

            $xml .= '</urlset>';

            return $xml;
        });

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }

    /**
     * Paginated English blog posts sitemap
     */
    public function postsSitemapEn(int $page = 1): Response
    {
        $xml = Cache::remember($this->cacheKey('posts.en.' . $page), self::CACHE_TTL, function () use ($page) {
            $contents = $this->applyPostBodyFilter(
                Content::withoutGlobalScopes()
                    ->where('status', 'published')
                    ->where('published_at', '<=', now())
                    ->orderByDesc('updated_at'),
                true // hanya post yang body EN-nya benar-benar terisi
            )->paginate(self::PER_PAGE, ['slug', 'body_raw', 'published_at', 'updated_at', 'last_partial_update_at', 'crawl_priority_score', 'hierarchy_level', 'search_volume', 'gsc_coverage_state', 'current_serp_position'], 'page', $page);

            $blogPrefix = $this->blogPrefix();
            $xml = $this->xmlHeader();
            $dupeSlugs = $this->duplicateGeneratedSlugs();

            foreach ($contents as $content) {
                if (in_array($content->slug, $dupeSlugs, true)) continue;

                $priority = $this->postPriority($content);
                $loc = url('/en/' . $blogPrefix . '/' . $content->slug);
                $xml .= $this->urlEntry($loc, $this->postLastmod($content), number_format($priority, 1), $this->postChangefreq($priority),
                    $this->hreflangLinks(url('/' . $blogPrefix . '/' . $content->slug), $loc));
            }

            $xml .= '</urlset>';

            return $xml;
        });

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

        // Default robots.txt — area privat & tidak berguna untuk indexasi
        // di-disallow agar crawl budget fokus ke halaman publik yang bernilai.
        $content  = "User-agent: *\n";
        $content .= "Allow: /\n";
        $content .= "Disallow: /admin/\n";
        $content .= "Disallow: /master/adminis-trator\n";
        $content .= "Disallow: /admin/dashboard\n";
        $content .= "Disallow: /buyer/\n";
        $content .= "Disallow: /login\n";
        $content .= "Disallow: /register\n";
        $content .= "Disallow: /payment/\n";
        // /search tidak di-disallow (pakai noindex via meta/X-Robots-Tag saja,
        // sesuai best practice Google agar bot tetap bisa melihat halamannya).
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

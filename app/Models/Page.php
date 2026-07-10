<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use \App\Traits\HasSeoMeta;

    protected $fillable = [
        'title',
        'slug',
        'template',
        'hero_headline',
        'hero_subheadline',
        'hero_cta_text',
        'hero_cta_url',
        'hero_cta_text_2',
        'hero_cta_url_2',
        'hero_image',
        'hero_video_url',
        'hero_features',
        'hero_bg_color',
        'html_content',
        'css_content',
        'meta_title',
        'meta_description',
        'is_homepage',
        'is_published',
    ];

    protected $casts = [
        'is_homepage' => 'boolean',
        'is_published' => 'boolean',
        'hero_features' => 'array',
    ];

    public function renderContent(): string
    {
        $content = $this->html_content ?? '';

        // Match seofast-posts-grid — supports extra classes like 'anim-stagger'
        $content = preg_replace_callback(
            '/<div[^>]*class="[^"]*seofast-posts-grid[^"]*"[^>]*data-columns="(\d+)"[^>]*data-limit="(\d+)"[^>]*>.*?<\/div>/s',
            function ($matches) {
                return self::renderPostsGrid((int) $matches[1], (int) $matches[2]);
            },
            $content
        );

        // Match seofast-products-grid — supports extra classes
        $content = preg_replace_callback(
            '/<div[^>]*class="[^"]*seofast-products-grid[^"]*"[^>]*data-columns="(\d+)"[^>]*data-limit="(\d+)"[^>]*>.*?<\/div>/s',
            function ($matches) {
                return self::renderProductsGrid((int) $matches[1], (int) $matches[2]);
            },
            $content
        );

        // Match seofast-featured-product
        $content = preg_replace_callback(
            '/<div[^>]*class="[^"]*seofast-featured-product[^"]*"[^>]*>.*?<\/div>/s',
            function () {
                return self::renderFeaturedProduct();
            },
            $content
        );

        // Match seofast-hot-posts — supports extra classes
        $content = preg_replace_callback(
            '/<div[^>]*class="[^"]*seofast-hot-posts[^"]*"[^>]*data-limit="(\d+)"[^>]*>.*?<\/div>/s',
            function ($matches) {
                return self::renderHotPosts((int) $matches[1]);
            },
            $content
        );

        return $content;
    }

    public static function renderPostsGrid(int $cols, int $limit): string
    {
        $posts = \App\Models\Content::where('status', 'published')
            ->whereNotNull('body_raw')
            ->where('published_at', '<=', now())
            ->where('body_raw', '!=', '{"id":""}')
            ->where('body_raw', '!=', '{"en":""}')
            ->where(function($q) {
                $q->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(body_raw, '$.id')) != ''")
                  ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(body_raw, '$.en')) != ''");
            })
            ->with('siloBlueprint')
            ->orderBy('published_at', 'desc')
            ->take($limit)
            ->get();

        if ($posts->isEmpty()) {
            return '<p class="text-slate-500 text-center py-8">No posts yet.</p>';
        }

        $gridClass = match ($cols) {
            1 => 'grid-cols-1',
            2 => 'sm:grid-cols-2',
            3 => 'sm:grid-cols-2 lg:grid-cols-3',
            4 => 'sm:grid-cols-2 lg:grid-cols-4',
            6 => 'sm:grid-cols-3 lg:grid-cols-6',
            default => 'sm:grid-cols-2 lg:grid-cols-3',
        };

        $html = '<div class="grid gap-6 ' . $gridClass . '">';
        foreach ($posts as $post) {
            $html .= view('blog.partials.card', ['post' => $post])->render();
        }
        $html .= '</div>';

        return $html;
    }

    public static function renderProductsGrid(int $cols, int $limit): string
    {
        $products = \App\Models\Product::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();

        if ($products->isEmpty()) {
            return '<p class="text-slate-500 text-center py-8">No products yet.</p>';
        }

        $gridClass = match ($cols) {
            1 => 'grid-cols-1',
            2 => 'sm:grid-cols-2',
            3 => 'sm:grid-cols-2 lg:grid-cols-3',
            4 => 'sm:grid-cols-2 lg:grid-cols-4',
            6 => 'sm:grid-cols-3 lg:grid-cols-6',
            default => 'sm:grid-cols-2 lg:grid-cols-3',
        };

        $html = '<div class="grid gap-6 ' . $gridClass . '">';
        foreach ($products as $product) {
            $html .= view('products.partials.card', ['product' => $product])->render();
        }
        $html .= '</div>';

        return $html;
    }

    public static function renderFeaturedProduct(): string
    {
        $product = \App\Models\Product::where('is_active', true)
            ->where('is_featured', true)
            ->first();

        if (!$product) {
            $product = \App\Models\Product::where('is_active', true)
                ->orderBy('purchase_count', 'desc')
                ->first();
        }

        if (!$product) {
            return '';
        }

        $route       = route('products.show', $product->slug);
        $contactUrl  = url('/contact');
        $rawFeatures = $product->features;
        if (is_string($rawFeatures)) { $rawFeatures = json_decode($rawFeatures, true) ?? []; }
        $features    = is_array($rawFeatures) ? array_slice($rawFeatures, 0, 5) : [];
        $badge       = $product->is_featured ? 'Produk Unggulan' : 'Best Seller';
        $priceLabel  = $product->displayPriceFormatted();
        $soldCount   = number_format($product->purchase_count ?? 0);
        $name        = e($product->name);
        $desc        = e($product->description);
        $isDev       = $product->isDevelopment();
        $origLabel   = $product->originalPriceFormatted();
        $priceHeading = $isDev ? 'Harga Development' : 'Harga Mulai';
        $devBadgeHtml = $isDev ? '<div style="font-size:0.8rem;color:#94a3b8;margin-top:0.3rem"><span style="text-decoration:line-through;opacity:0.6">' . $origLabel . '</span> <span style="display:inline-block;padding:0.1rem 0.5rem;font-size:0.65rem;font-weight:700;background:rgba(251,191,36,0.15);color:#fbbf24;border-radius:9999px;text-transform:uppercase">Early Bird</span></div>' : '';

        // Build feature pills
        $pillsHtml = '';
        foreach ($features as $f) {
            $label = is_string($f) ? $f : ($f['title'] ?? $f['feature'] ?? '');
            if ($label) {
                $pillsHtml .= '<li class="flex items-center gap-2 text-sm text-slate-200">'
                    . '<svg class="w-4 h-4 text-indigo-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
                    . e($label)
                    . '</li>';
            }
        }

        return <<<HTML
<div style="background:linear-gradient(135deg,#0f172a 0%,#1e1b4b 60%,#1e293b 100%);border-radius:1.5rem;overflow:hidden;position:relative;color:#fff">
  <!-- Glow blobs -->
  <div style="position:absolute;top:-8rem;right:-6rem;width:32rem;height:32rem;background:radial-gradient(circle,rgba(99,102,241,0.18),transparent 70%);pointer-events:none"></div>
  <div style="position:absolute;bottom:-6rem;left:-4rem;width:24rem;height:24rem;background:radial-gradient(circle,rgba(168,85,247,0.12),transparent 70%);pointer-events:none"></div>
  <!-- Top accent bar -->
  <div style="height:3px;background:linear-gradient(90deg,#6366f1,#a78bfa,#f59e0b);background-size:200% 100%;animation:shimmer 3s ease-in-out infinite"></div>
  <div style="position:relative;padding:2.5rem 2rem" class="md:p-14">
    <!-- Badge -->
    <div style="display:inline-flex;align-items:center;gap:0.4rem;font-size:0.7rem;font-weight:700;letter-spacing:0.15em;text-transform:uppercase;padding:0.35rem 0.85rem;border-radius:9999px;background:rgba(251,191,36,0.15);color:#fbbf24;border:1px solid rgba(251,191,36,0.3);margin-bottom:1.5rem">
      <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/></svg>
      {$badge}
    </div>
    <!-- Main grid -->
    <div class="grid" style="grid-template-columns:1fr;gap:2.5rem" id="fp-grid">
      <!-- Left: Info -->
      <div>
        <h2 style="font-family:'Outfit',sans-serif;font-weight:800;font-size:clamp(1.75rem,4vw,3rem);line-height:1.1;margin-bottom:0.75rem">{$name}</h2>
        <p style="color:#cbd5e1;font-size:1rem;line-height:1.65;margin-bottom:1.5rem;max-width:38rem">{$desc}</p>
        <!-- Feature list -->
        <ul style="list-style:none;padding:0;margin:0 0 1.75rem;display:flex;flex-direction:column;gap:0.55rem">{$pillsHtml}</ul>
        <!-- CTAs -->
        <div style="display:flex;flex-wrap:wrap;gap:0.875rem;align-items:center">
          <a href="{$route}" style="display:inline-flex;align-items:center;gap:0.5rem;padding:0.8rem 1.6rem;background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.18);border-radius:0.75rem;color:#fff;font-weight:600;font-size:0.9rem;text-decoration:none;transition:background 0.2s" onmouseover="this.style.background='rgba(255,255,255,0.15)'" onmouseout="this.style.background='rgba(255,255,255,0.08)'">
            Lihat Detail
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
          </a>
          <a href="{$contactUrl}" style="display:inline-flex;align-items:center;gap:0.5rem;padding:0.8rem 1.6rem;background:linear-gradient(135deg,#6366f1,#9333ea);border-radius:0.75rem;color:#fff;font-weight:700;font-size:0.9rem;text-decoration:none;box-shadow:0 8px 24px rgba(99,102,241,0.35);transition:opacity 0.2s,transform 0.2s" onmouseover="this.style.opacity='0.9';this.style.transform='scale(1.03)'" onmouseout="this.style.opacity='1';this.style.transform='scale(1)'">
            Konsultasi Gratis
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z"/></svg>
          </a>
        </div>
      </div>
      <!-- Right: Stats card -->
      <div style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.1);border-radius:1.25rem;padding:1.75rem;backdrop-filter:blur(12px)">
        <!-- Price -->
        <div style="text-align:center;padding-bottom:1.25rem;margin-bottom:1.25rem;border-bottom:1px solid rgba(255,255,255,0.1)">
          <div style="font-size:0.7rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:#94a3b8;margin-bottom:0.4rem">{$priceHeading}</div>
          <div style="font-family:'Outfit',sans-serif;font-weight:900;font-size:2rem;background:linear-gradient(135deg,#818cf8,#c084fc);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">{$priceLabel}</div>
          {$devBadgeHtml}
        </div>
        <!-- Stats grid -->
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.25rem">
          <div style="text-align:center;padding:0.875rem;background:rgba(99,102,241,0.1);border-radius:0.875rem;border:1px solid rgba(99,102,241,0.2)">
            <div style="font-family:'Outfit',sans-serif;font-weight:800;font-size:1.5rem;color:#818cf8">{$soldCount}</div>
            <div style="font-size:0.7rem;color:#94a3b8;margin-top:0.2rem">Terjual</div>
          </div>
          <div style="text-align:center;padding:0.875rem;background:rgba(168,85,247,0.1);border-radius:0.875rem;border:1px solid rgba(168,85,247,0.2)">
            <div style="font-family:'Outfit',sans-serif;font-weight:800;font-size:1.5rem;color:#c084fc">24/7</div>
            <div style="font-size:0.7rem;color:#94a3b8;margin-top:0.2rem">Support</div>
          </div>
        </div>
        <!-- Details -->
        <div style="display:flex;flex-direction:column;gap:0.6rem">
          <div style="display:flex;justify-content:space-between;align-items:center;font-size:0.825rem"><span style="color:#94a3b8">Lisensi</span><span style="color:#e2e8f0;font-weight:600">Per Domain</span></div>
          <div style="display:flex;justify-content:space-between;align-items:center;font-size:0.825rem"><span style="color:#94a3b8">Update</span><span style="color:#e2e8f0;font-weight:600">Seumur Hidup</span></div>
          <div style="display:flex;justify-content:space-between;align-items:center;font-size:0.825rem"><span style="color:#94a3b8">Multi-Tenant</span><span style="color:#34d399;font-weight:600">✓ Tersedia</span></div>
        </div>
      </div>
    </div>
  </div>
</div>
<style>
@media(min-width:1024px){#fp-grid{grid-template-columns:3fr 2fr!important}}
</style>
HTML;
    }

    public static function renderHotPosts(int $limit): string
    {
        $posts = \App\Models\Content::where('status', 'published')
            ->whereNotNull('body_raw')
            ->where('published_at', '<=', now())
            ->where('body_raw', '!=', '{"id":""}')
            ->where('body_raw', '!=', '{"en":""}')
            ->where(function($q) {
                $q->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(body_raw, '$.id')) != ''")
                  ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(body_raw, '$.en')) != ''");
            })
            ->orderBy('published_at', 'desc')
            ->take($limit)
            ->get();

        if ($posts->isEmpty()) {
            return '';
        }

        $html = '<div class="space-y-4">';
        $rank = 1;
        foreach ($posts as $post) {
            $color = match ($rank) { 1 => 'from-amber-400 to-amber-600', 2 => 'from-slate-400 to-slate-500', 3 => 'from-amber-700 to-amber-800', default => 'from-slate-300 to-slate-400' };
            $html .= <<<HTML
<a href="{$post->slug}" class="flex items-center gap-4 p-4 rounded-2xl bg-white border border-slate-200 hover:border-slate-300 hover:shadow-md transition-all group">
    <div class="w-10 h-10 rounded-xl bg-gradient-to-br {$color} flex items-center justify-center text-white font-bold text-sm shrink-0">{$rank}</div>
    <div class="flex-1 min-w-0">
        <h4 class="font-outfit font-bold text-sm text-slate-900 line-clamp-1 group-hover:text-indigo-600 transition-colors">{$post->title}</h4>
        <p class="text-xs text-slate-500 mt-0.5">{$post->published_at?->format('M d, Y')} &middot; {$post->target_keyword}</p>
    </div>
    <svg class="w-5 h-5 text-slate-300 group-hover:text-indigo-500 transition-colors shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
</a>
HTML;
            $rank++;
        }
        $html .= '</div>';

        return $html;
    }

    public static function templates(): array
    {
        return [
            'default' => 'Default (no hero)',
            'hero-centered' => 'Hero Centered',
            'hero-split' => 'Hero Split (text + image)',
            'hero-image' => 'Hero Full Background Image',
            'hero-video' => 'Hero Video Background',
            'hero-cta' => 'Hero Bold CTA',
        ];
    }
}

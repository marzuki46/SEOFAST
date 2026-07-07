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

        $content = preg_replace_callback(
            '/<div[^>]*class="seofast-posts-grid"[^>]*data-columns="(\d+)"[^>]*data-limit="(\d+)"[^>]*>.*?<\/div>/s',
            function ($matches) {
                return self::renderPostsGrid((int) $matches[1], (int) $matches[2]);
            },
            $content
        );

        $content = preg_replace_callback(
            '/<div[^>]*class="seofast-products-grid"[^>]*data-columns="(\d+)"[^>]*data-limit="(\d+)"[^>]*>.*?<\/div>/s',
            function ($matches) {
                return self::renderProductsGrid((int) $matches[1], (int) $matches[2]);
            },
            $content
        );

        return $content;
    }

    public static function renderPostsGrid(int $cols, int $limit): string
    {
        $posts = \App\Models\Content::where('status', 'published')
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

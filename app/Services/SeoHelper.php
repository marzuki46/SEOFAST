<?php

namespace App\Services;

use App\Models\SeoMeta;
use App\Models\SystemSetting;

class SeoHelper
{
    /**
     * Resolve a dynamic title template string.
     * Variables: {site_name}, {page_title}, {category_name}, {year}, {separator}
     */
    public static function resolveTemplate(string $template, array $vars = []): string
    {
        $siteName  = SystemSetting::get('site_name', config('app.name', 'SEOFAST'));
        $separator = SystemSetting::get('title_separator', '|');

        $defaults = [
            '{site_name}'  => $siteName,
            '{separator}'  => $separator,
            '{year}'       => date('Y'),
        ];

        $all = array_merge($defaults, $vars);

        return str_replace(array_keys($all), array_values($all), $template);
    }

    /**
     * Build the <title> string for a blog post.
     */
    public static function postTitle(string $postTitle, ?string $customTitle = null): string
    {
        if ($customTitle) {
            return self::resolveTemplate($customTitle, ['{page_title}' => $postTitle]);
        }

        $template = SystemSetting::get('title_template_post', '{page_title} {separator} {site_name}');
        return self::resolveTemplate($template, ['{page_title}' => $postTitle]);
    }

    /**
     * Build the <title> string for a category page.
     */
    public static function categoryTitle(string $categoryName): string
    {
        $template = SystemSetting::get('title_template_category', '{category_name} Articles {separator} {site_name}');
        return self::resolveTemplate($template, ['{category_name}' => $categoryName]);
    }

    /**
     * Build the <title> string for a static page.
     */
    public static function pageTitle(string $pageTitle, ?string $customTitle = null): string
    {
        if ($customTitle) {
            return self::resolveTemplate($customTitle, ['{page_title}' => $pageTitle]);
        }

        $template = SystemSetting::get('title_template_page', '{page_title} {separator} {site_name}');
        return self::resolveTemplate($template, ['{page_title}' => $pageTitle]);
    }

    /**
     * Build the <title> string for the homepage.
     */
    public static function homepageTitle(): string
    {
        $custom = SystemSetting::get('seo_global_meta_title');
        if ($custom) return $custom;

        $siteName = SystemSetting::get('site_name', config('app.name', 'SEOFAST'));
        $tagline  = SystemSetting::get('site_tagline');
        $sep      = SystemSetting::get('title_separator', '|');

        return $tagline ? "{$siteName} {$sep} {$tagline}" : $siteName;
    }

    /**
     * Inject GA4 / GTM snippet into <head> if configured.
     */
    public static function trackingHeadScripts(): string
    {
        $html = '';

        $gaId  = SystemSetting::get('seo_global_google_analytics_id');
        $gtmId = SystemSetting::get('seo_global_gtm_id');
        $fbPx  = SystemSetting::get('seo_global_fb_pixel_id');

        if ($gaId) {
            $html .= "<script async src=\"https://www.googletagmanager.com/gtag/js?id={$gaId}\"></script>\n";
            $html .= "<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','{$gaId}');</script>\n";
        }

        if ($gtmId) {
            $html .= "<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','{$gtmId}');</script>\n";
        }

        if ($fbPx) {
            $html .= "<script>!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');fbq('init','{$fbPx}');fbq('track','PageView');</script>\n";
        }

        return $html;
    }

    /**
     * GTM noscript (must be first inside <body>)
     */
    public static function trackingBodyStart(): string
    {
        $gtmId = SystemSetting::get('seo_global_gtm_id');
        if (!$gtmId) return '';

        return "<noscript><iframe src=\"https://www.googletagmanager.com/ns.html?id={$gtmId}\" height=\"0\" width=\"0\" style=\"display:none;visibility:hidden\"></iframe></noscript>\n";
    }

    /**
     * Build full SSR meta tags for a model (Content, Page, Product).
     * Returns array of meta values for use in Blade @section.
     */
    public static function getModelMeta(mixed $model): array
    {
        $seoMeta = $model->seoMeta ?? null;

        return [
            'title'       => $seoMeta?->title ?: ($model->meta_title ?? $model->title ?? null),
            'description' => $seoMeta?->description ?: ($model->meta_description ?? null),
            'canonical'   => $seoMeta?->canonical ?: request()->url(),
            'robots'      => $seoMeta?->robots ?: SystemSetting::get('seo_indexing_robots', 'index, follow'),
            'og_image'    => $seoMeta?->og_image ?: SystemSetting::get('seo_global_og_image', asset('assets/og-default.jpg')),
            'og_title'    => $seoMeta?->og_title ?: null,
            'og_desc'     => $seoMeta?->og_description ?: null,
        ];
    }

    /**
     * Generate BreadcrumbList JSON-LD.
     */
    public static function breadcrumbSchema(array $crumbs): string
    {
        $items = [];
        foreach ($crumbs as $i => $crumb) {
            $items[] = [
                '@type'    => 'ListItem',
                'position' => $i + 1,
                'name'     => $crumb['name'],
                'item'     => $crumb['url'],
            ];
        }

        return '<script type="application/ld+json">' . json_encode([
            '@context'        => 'https://schema.org',
            '@type'           => 'BreadcrumbList',
            'itemListElement' => $items,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>';
    }

    /**
     * Apply lazy loading to all <img> tags in HTML content and ensure alt text exists.
     */
    public static function lazyLoadImages(string $html): string
    {
        // Add loading="lazy" and decoding="async" if missing
        $html = preg_replace('/<img(?![^>]*loading=)([^>]*)>/i', '<img loading="lazy" decoding="async" $1>', $html);
        
        // Add alt="" if missing (for accessibility and SEO)
        $html = preg_replace('/<img(?![^>]*alt=)([^>]*)>/i', '<img alt="Image" $1>', $html);

        return $html;
    }

    /**
     * Generate dynamic schema markup for models.
     */
    public static function renderSchema(mixed $model): string
    {
        if (!$model) return '';

        $schemaType = 'WebPage';
        $seoMeta = $model->seoMeta ?? null;

        // Custom schema from settings or override
        if ($seoMeta && !empty($seoMeta->schema['@type'])) {
            $schemaType = $seoMeta->schema['@type'];
        } else {
            // Determine default based on model type
            if ($model instanceof \App\Models\Content) {
                $schemaType = 'Article';
            } elseif ($model instanceof \App\Models\Product) {
                $schemaType = 'Product';
            }
        }

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => $schemaType,
        ];

        // Auto-fill common properties
        if ($schemaType === 'Article' || $schemaType === 'NewsArticle' || $schemaType === 'BlogPosting') {
            $schema['headline'] = $seoMeta?->title ?: ($model->title ?? '');
            $schema['description'] = $seoMeta?->description ?: ($model->meta_description ?? '');
            
            if (isset($model->featured_image_url)) {
                $schema['image'] = [$model->featured_image_url];
            } elseif ($seoMeta?->og_image) {
                $schema['image'] = [$seoMeta->og_image];
            }

            if (isset($model->published_at)) {
                $schema['datePublished'] = $model->published_at->toIso8601String();
            }
            if (isset($model->updated_at)) {
                $schema['dateModified'] = $model->updated_at->toIso8601String();
            }

            $schema['author'] = [
                '@type' => 'Organization',
                'name' => SystemSetting::get('site_name', config('app.name')),
                'url' => url('/'),
            ];

            $schema['publisher'] = [
                '@type' => 'Organization',
                'name' => SystemSetting::get('site_name', config('app.name')),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => SystemSetting::get('favicon_url', asset('favicon.ico'))
                ]
            ];
            $schema['mainEntityOfPage'] = [
                '@type' => 'WebPage',
                '@id' => request()->url(),
            ];
        } elseif ($schemaType === 'Product') {
            $schema['name'] = $model->title ?? '';
            $schema['description'] = $seoMeta?->description ?: ($model->meta_description ?? '');
            if (isset($model->price)) {
                $schema['offers'] = [
                    '@type' => 'Offer',
                    'price' => $model->price,
                    'priceCurrency' => 'IDR',
                    'availability' => 'https://schema.org/InStock',
                ];
            }
        }

        // Allow overriding via stored schema JSON if set explicitly
        if ($seoMeta && is_array($seoMeta->schema)) {
            $schema = array_merge($schema, $seoMeta->schema);
        }

        return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>';
    }

    /**
     * Resolve internal links inside HTML content based on the target locale.
     * Ensures that links point to the translated URL only if it exists (200 OK).
     */
    public static function resolveInternalLinks(string $html, string $targetLocale = 'id'): string
    {

        $appUrl = rtrim(config('app.url'), '/');
        $blogPrefix = \App\Models\SystemSetting::get('permalink_blog', 'blog');
        
        // Find all <a> tags with href
        return preg_replace_callback('/<a\s+[^>]*href=["\']([^"\']+)["\'][^>]*>(.*?)<\/a>/i', function ($matches) use ($appUrl, $blogPrefix, $targetLocale) {
            $fullAnchorTag = $matches[0];
            $href = $matches[1];
            $anchorText = $matches[2];

            // Only process absolute internal URLs or relative internal URLs starting with /blog/
            $isInternal = str_starts_with($href, $appUrl) || str_starts_with($href, '/' . $blogPrefix . '/');
            
            if ($isInternal) {
                // Extract the slug
                $path = str_replace($appUrl, '', $href);
                $path = ltrim($path, '/');
                
                if (str_starts_with($path, $blogPrefix . '/')) {
                    $originalSlug = substr($path, strlen($blogPrefix) + 1);
                    
                    $post = \App\Models\Content::where(function($q) use ($originalSlug) {
                        $q->where('slug', $originalSlug)->orWhere('slug', 'LIKE', '%"id":"'.$originalSlug.'"%');
                    })->first();
                    
                    if ($post) {
                        // If the target post is NOT published, hide the link to prevent 404s and exposing drafts
                        if ($post->status !== 'published') {
                            return $anchorText;
                        }

                        if ($targetLocale !== 'id') {
                            // Always force the link to the target locale (e.g., /en/blog/...)
                            $translatedSlug = $post->slug ?: $originalSlug;
                            $newHref = url("/{$targetLocale}/{$blogPrefix}/{$translatedSlug}");
                            return str_replace($href, $newHref, $fullAnchorTag);
                        }
                    }
                }
            }

            return $fullAnchorTag; // Return unmodified if no translation needed or not internal
        }, $html);
    }
}

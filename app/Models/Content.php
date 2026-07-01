<?php

namespace App\Models;

use App\Models\Traits\TenantAwareTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Content extends Model
{
    use TenantAwareTrait, SoftDeletes, \App\Traits\HasSeoMeta;

    protected $fillable = [
        'tenant_id',
        'silo_blueprint_id',
        'target_keyword',
        'slug',
        'meta_title',
        'meta_description',
        'hierarchy_level',
        'search_volume',
        'kgr_score',
        'cqi_score',
        'semantic_depth_score',
        'entity_coverage_score',
        'readability_score',
        'vector_embedding_id',
        'embedding_model_version',
        'embedding_generated_at',
        'body_raw',
        'rendered_html_path',
        'content_hash',
        'last_render_intact',
        'status',
        'published_at',
        'last_partial_update_at',
        'gsc_coverage_state',
        'current_serp_position',
        'ranking_last_checked_at',
        // Phase 4: Image metadata
        'featured_image_url',
        'featured_image_alt',
        'featured_image_caption',
        // Phase 3: Crawl priority
        'crawl_priority_score',
        'is_ghost_published',
        'parent_id',
    ];

    protected function casts(): array
    {
        return [
            'search_volume' => 'integer',
            'kgr_score' => 'decimal:2',
            'cqi_score' => 'decimal:2',
            'semantic_depth_score' => 'decimal:2',
            'entity_coverage_score' => 'decimal:2',
            'readability_score' => 'decimal:2',
            'crawl_priority_score' => 'decimal:2',
            'embedding_generated_at' => 'datetime',
            'last_render_intact' => 'boolean',
            'is_ghost_published' => 'boolean',
            'published_at' => 'datetime',
            'last_partial_update_at' => 'datetime',
            'ranking_last_checked_at' => 'datetime',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function siloBlueprint(): BelongsTo
    {
        return $this->belongsTo(SiloBlueprint::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Content::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Content::class, 'parent_id');
    }

    public function sourceLinks(): HasMany
    {
        return $this->hasMany(DeterministicLink::class, 'source_content_id');
    }

    public function targetLinks(): HasMany
    {
        return $this->hasMany(DeterministicLink::class, 'target_content_id');
    }

    public function schemaMarkups(): HasMany
    {
        return $this->hasMany(SchemaMarkup::class);
    }

    public function canonicalMappings(): HasMany
    {
        return $this->hasMany(CanonicalMapping::class, 'content_id');
    }

    public function urlInspections(): HasMany
    {
        return $this->hasMany(GscUrlInspection::class);
    }

    public function latestUrlInspection()
    {
        return $this->hasOne(GscUrlInspection::class)->latestOfMany('inspected_at');
    }

    public function searchAnalytics(): HasMany
    {
        return $this->hasMany(GscSearchAnalytics::class);
    }

    public function feedback(): HasOne
    {
        return $this->hasOne(SeoFeedbackLoop::class);
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function isPillar(): bool
    {
        return $this->hierarchy_level === 'pillar';
    }

    // --- TRANSPARENT JSON HANDLING ---
    // This allows us to keep the database JSON columns (and their MySQL constraints)
    // while the rest of the application ONLY sees plain strings. No multi-language complexity!
    
    protected function getJsonField(string $key): ?string
    {
        $val = $this->attributes[$key] ?? null;
        
        // Handle double/triple encoded JSON and arrays
        while (is_string($val) && (str_starts_with(trim($val), '{') || str_starts_with(trim($val), '"{'))) {
            $decoded = json_decode(trim($val, '"'), true);
            if (is_array($decoded)) {
                $val = $decoded['id'] ?? current($decoded);
            } else {
                break;
            }
        }
        
        while (is_array($val)) {
            $val = $val['id'] ?? current($val);
        }

        return is_string($val) ? $val : (string) $val;
    }

    protected function setJsonField(string $key, $value): void
    {
        if ($value === null) {
            $this->attributes[$key] = null;
            return;
        }

        // Prevent double encoding if the value is already a JSON string from a previous process
        if (is_string($value) && (str_starts_with(trim($value), '{') || str_starts_with(trim($value), '"{'))) {
            $decoded = json_decode(trim($value, '"'), true);
            if (is_array($decoded) && isset($decoded['id'])) {
                $value = $decoded['id'];
            }
        }
        
        // Wrap the plain string in JSON to satisfy MySQL JSON constraints
        $this->attributes[$key] = json_encode(['id' => $value, 'en' => $value], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_IGNORE);
    }

    public function getSlugAttribute() { return $this->getJsonField('slug'); }
    public function setSlugAttribute($val) { $this->setJsonField('slug', $val); }

    public function getMetaTitleAttribute() { return $this->getJsonField('meta_title'); }
    public function setMetaTitleAttribute($val) { $this->setJsonField('meta_title', $val); }

    public function getMetaDescriptionAttribute() { return $this->getJsonField('meta_description'); }
    public function setMetaDescriptionAttribute($val) { $this->setJsonField('meta_description', $val); }

    public function getBodyRawAttribute() { return $this->getJsonField('body_raw'); }
    public function setBodyRawAttribute($val) { $this->setJsonField('body_raw', $val); }

    public function getFeaturedImageAltAttribute() { return $this->getJsonField('featured_image_alt'); }
    public function setFeaturedImageAltAttribute($val) { $this->setJsonField('featured_image_alt', $val); }

    public function getFeaturedImageCaptionAttribute() { return $this->getJsonField('featured_image_caption'); }
    public function setFeaturedImageCaptionAttribute($val) { $this->setJsonField('featured_image_caption', $val); }

    /**
     * Get display title.
     */
    public function getTitleAttribute(): string
    {
        $metaTitle = $this->meta_title;
        $slug = $this->slug;
        return is_string($metaTitle) && !empty($metaTitle) ? $metaTitle : (is_string($slug) && !empty($slug) ? ucfirst(str_replace('-', ' ', $slug)) : \Illuminate\Support\Str::title($this->target_keyword));
    }

    /**
     * Get excerpt or fallback snippet.
     */
    public function getExcerptAttribute(): string
    {
        $metaDesc = $this->meta_description;
        if (!empty($metaDesc)) {
            return $metaDesc;
        }

        $body = $this->body_raw;
        $text = strip_tags(preg_replace('/#+/', '', $body ?? ''));
        $text = preg_replace('/\[([^\]]+)\]\([^)]+\)/', '$1', $text); // Remove markdown links but keep text
        return \Illuminate\Support\Str::words($text, 25, '...');
    }

    /**
     * Convert body_raw markdown to styled HTML.
     */
    public function getHtmlBodyAttribute(): string
    {
        $markdown = $this->body_raw;
        if (!$markdown) return '';

        // Pass HTML output as is (Phase 6 now generates HTML instead of Markdown)
        $html = $markdown;

        // Replace headings: H3
        $html = preg_replace_callback('/^\s*###\s+(.+)$/m', function($matches) {
            $id = \Illuminate\Support\Str::slug($matches[1]);
            return '<h3 id="' . $id . '" class="text-xl font-bold mt-6 mb-3 text-gray-800">' . $matches[1] . '</h3>';
        }, $html);
        // Replace headings: H2
        $html = preg_replace_callback('/^\s*##\s+(.+)$/m', function($matches) {
            $id = \Illuminate\Support\Str::slug($matches[1]);
            return '<h2 id="' . $id . '" class="text-2xl font-bold mt-8 mb-4 text-gray-900 border-b pb-2 border-gray-100">' . $matches[1] . '</h2>';
        }, $html);
        // Replace headings: H1
        $html = preg_replace('/^\s*#\s+(.+)$/m', '<h1 class="text-3xl font-extrabold mt-10 mb-6 text-gray-900">$1</h1>', $html);

        // Bold
        $html = preg_replace('/\*\*(.+?)\*\*/', '<strong class="font-semibold text-gray-950">$1</strong>', $html);
        // Italics
        $html = preg_replace('/\*(.+?)\*/', '<em class="italic text-gray-800">$1</em>', $html);
        // Links
        $html = preg_replace('/\[([^\]]+)\]\(([^)]+)\)/', '<a href="$2" class="text-indigo-600 hover:text-indigo-800 font-medium underline">$1</a>', $html);

        // Unordered lists
        $html = preg_replace('/^\s*[\-\*]\s+(.+)$/m', '<li class="ml-6 list-disc text-gray-700 my-1">$1</li>', $html);
        $html = preg_replace('/(<li class="ml-6 list-disc.*?<\/li>)+/s', '<ul class="space-y-1 my-4">$0</ul>', $html);

        // Ordered lists
        $html = preg_replace('/^\s*\d+\.\s+(.+)$/m', '<li class="ml-6 list-decimal text-gray-700 my-1">$1</li>', $html);
        $html = preg_replace('/(<li class="ml-6 list-decimal.*?<\/li>)+/s', '<ol class="space-y-1 my-4">$0</ol>', $html);

        // Code blocks
        $html = preg_replace('/```(?:[a-zA-Z0-9]+)?\s*([\s\S]*?)```/s', '<pre class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto my-6 font-mono text-sm shadow-inner"><code class="language-json">$1</code></pre>', $html);

        // Paragraphs
        $paragraphs = explode("\n\n", $html);
        foreach ($paragraphs as &$p) {
            $p = trim($p);
            if ($p && 
                strpos($p, '<h') !== 0 && 
                strpos($p, '<ul') !== 0 && 
                strpos($p, '<ol') !== 0 && 
                strpos($p, '<pre') !== 0 && 
                strpos($p, '<li') !== 0
            ) {
                $p = '<p class="text-lg text-gray-700 leading-relaxed my-4">' . nl2br($p) . '</p>';
            }
        }
        $html = implode("\n", $paragraphs);

        // Decode code block contents back to html for display
        $html = preg_replace_callback('/<pre.*?><code.*?>(.*?)<\/code><\/pre>/s', function($matches) {
            return str_replace(['&lt;', '&gt;', '&amp;', '&quot;', '&#039;'], ['<', '>', '&', '"', "'"], $matches[0]);
        }, $html);

        // Parse Midtrans Shortcodes
        $html = preg_replace_callback('/\[midtrans_checkout\s+product="([^"]+)"\]/', function($matches) {
            $slug = $matches[1];
            $product = \App\Models\Product::where('slug', $slug)->first();
            
            if ($product && $product->is_active) {
                // Render the blade component into HTML string
                return \Illuminate\Support\Facades\Blade::render(
                    '<x-midtrans-widget :product="$product" />', 
                    ['product' => $product]
                );
            }
            return ''; // Hide if product not found or inactive
        }, $html);

        // Resolve multi-language internal links based on current app locale
        $html = \App\Services\SeoHelper::resolveInternalLinks($html, app()->getLocale());

        // Apply lazy loading and ensure alt tags exist
        $html = \App\Services\SeoHelper::lazyLoadImages($html);

        return $html;
    }

    /**
     * Get parsed Table of Contents (TOC) array from body_raw.
     */
    public function getTocAttribute(): array
    {
        $markdown = $this->body_raw;
        if (!$markdown) return [];

        $toc = [];
        preg_match_all('/^\s*(#{2,3})\s+(.+)$/m', $markdown, $matches, PREG_SET_ORDER);
        
        foreach ($matches as $match) {
            $level = strlen(trim($match[1])); // 2 for ##, 3 for ###
            $title = trim($match[2]);
            $id = \Illuminate\Support\Str::slug($title);
            $toc[] = [
                'level' => $level,
                'title' => $title,
                'id' => $id
            ];
        }

        return $toc;
    }
}
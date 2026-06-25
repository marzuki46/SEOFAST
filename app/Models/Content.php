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
    use TenantAwareTrait, SoftDeletes, \App\Traits\HasSeoMeta, \Spatie\Translatable\HasTranslations;

    public $translatable = [
        'slug',
        'meta_title',
        'meta_description',
        'body_raw',
        'featured_image_alt',
        'featured_image_caption'
    ];

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

    /**
     * Get display title.
     */
    public function getTitleAttribute(): string
    {
        return $this->meta_title ?: ucfirst(str_replace('-', ' ', $this->slug));
    }

    /**
     * Convert body_raw markdown to styled HTML.
     */
    public function getHtmlBodyAttribute(): string
    {
        $markdown = $this->body_raw;
        if (!$markdown) return '';

        // Escape HTML tags to prevent XSS
        $html = htmlspecialchars($markdown, ENT_NOQUOTES, 'UTF-8');

        // Replace headings: H3
        $html = preg_replace('/^\s*###\s+(.+)$/m', '<h3 class="text-xl font-bold mt-6 mb-3 text-gray-800">$1</h3>', $html);
        // Replace headings: H2
        $html = preg_replace('/^\s*##\s+(.+)$/m', '<h2 class="text-2xl font-bold mt-8 mb-4 text-gray-900 border-b pb-2 border-gray-100">$1</h2>', $html);
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
}
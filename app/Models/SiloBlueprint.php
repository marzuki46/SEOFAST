<?php

namespace App\Models;

use App\Models\Traits\TenantAwareTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SiloBlueprint extends Model
{
    use TenantAwareTrait;

    protected $fillable = [
        'tenant_id',
        'silo_name',
        'content_framework',
        'seed_keyword',
        'target_language',
        'target_country',
        'visual_graph_data',
        'is_locked',
        'total_contents',
        'published_contents',
    ];

    protected function casts(): array
    {
        return [
            'visual_graph_data' => 'json',
            'is_locked' => 'boolean',
            'total_contents' => 'integer',
            'published_contents' => 'integer',
        ];
    }

    public function getFrameworkLabelAttribute(): string
    {
        $labels = [
            'default' => 'Default (AI Bebas)',
            'aida'    => 'AIDA (Attention → Interest → Desire → Action)',
            'pas'     => 'PAS (Problem → Agitate → Solution)',
            'how_to'  => 'How-To Guide (Step-by-Step)',
            'listicle' => 'Listicle (Daftar / Top X)',
        ];
        return $labels[$this->content_framework] ?? $labels['default'];
    }

    public static function frameworkOptions(): array
    {
        return [
            'default' => 'Default (AI Bebas)',
            'aida'    => 'AIDA — Attention → Interest → Desire → Action',
            'pas'     => 'PAS — Problem → Agitate → Solution',
            'how_to'  => 'How-To — Panduan Langkah demi Langkah',
            'listicle' => 'Listicle — Daftar / Top X',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function contents(): HasMany
    {
        return $this->hasMany(Content::class);
    }

    public function pillarContent()
    {
        return $this->contents()->where('hierarchy_level', 'pillar');
    }

    public function clusterContents()
    {
        return $this->contents()->where('hierarchy_level', 'cluster');
    }

    public function subClusterContents()
    {
        return $this->contents()->where('hierarchy_level', 'sub_cluster');
    }

    /**
     * Get category slug.
     */
    public function getSlugAttribute(): string
    {
        return \Illuminate\Support\Str::slug($this->silo_name);
    }
}
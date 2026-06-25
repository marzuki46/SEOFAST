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
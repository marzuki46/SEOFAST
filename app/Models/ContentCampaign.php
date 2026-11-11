<?php

namespace App\Models;

use App\Models\Traits\TenantAwareTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContentCampaign extends Model
{
    use SoftDeletes, TenantAwareTrait;

    protected $fillable = [
        'tenant_id', 'product_id', 'silo_blueprint_id', 'name', 'status', 'language',
        'funnel_mode', 'audience', 'brand_voice', 'niche', 'business_data', 'entity_seed',
        'keyword_prompt', 'entity_prompt', 'landing_prompt', 'content_prompt',
        'word_count_min', 'word_count_max', 'cta_label', 'cta_url',
        'auto_research', 'research_batch_size', 'posts_per_day', 'schedule_type',
        'fixed_hours', 'publish_status', 'destination_url', 'timezone', 'last_run_at', 'next_run_at',
    ];

    protected function casts(): array
    {
        return [
            'auto_research' => 'boolean',
            'research_batch_size' => 'integer',
            'word_count_min' => 'integer',
            'word_count_max' => 'integer',
            'posts_per_day' => 'integer',
            'last_run_at' => 'datetime',
            'next_run_at' => 'datetime',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function siloBlueprint(): BelongsTo
    {
        return $this->belongsTo(SiloBlueprint::class);
    }

    public function topics(): HasMany
    {
        return $this->hasMany(ContentTopic::class, 'campaign_id');
    }

    public function targets(): HasMany
    {
        return $this->hasMany(ContentCampaignTarget::class, 'campaign_id')->orderBy('sort_order');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(ContentCampaignSchedule::class, 'campaign_id');
    }

    public function contents(): HasMany
    {
        return $this->hasMany(Content::class, 'campaign_id');
    }
}

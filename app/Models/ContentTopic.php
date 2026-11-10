<?php

namespace App\Models;

use App\Models\Traits\TenantAwareTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContentTopic extends Model
{
    use TenantAwareTrait;

    protected $fillable = [
        'tenant_id', 'campaign_id', 'product_id', 'silo_blueprint_id', 'content_id',
        'campaign_target_id',
        'funnel_stage', 'title', 'primary_keyword', 'normalized_keyword', 'intent',
        'angle', 'entity_keywords', 'status', 'source', 'similarity_score', 'rejection_reason',
    ];

    protected function casts(): array
    {
        return ['entity_keywords' => 'array', 'similarity_score' => 'decimal:4'];
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(ContentCampaign::class, 'campaign_id');
    }

    public function campaignTarget(): BelongsTo
    {
        return $this->belongsTo(ContentCampaignTarget::class, 'campaign_target_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function siloBlueprint(): BelongsTo
    {
        return $this->belongsTo(SiloBlueprint::class);
    }

    public function content(): BelongsTo
    {
        return $this->belongsTo(Content::class);
    }
}

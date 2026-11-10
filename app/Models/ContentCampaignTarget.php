<?php

namespace App\Models;

use App\Models\Traits\TenantAwareTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContentCampaignTarget extends Model
{
    use TenantAwareTrait;

    protected $fillable = [
        'tenant_id', 'campaign_id', 'product_id', 'page_id', 'keyword', 'normalized_keyword',
        'sort_order', 'status', 'landing_url', 'anchor_variations', 'landing_data', 'error_message',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'anchor_variations' => 'array',
            'landing_data' => 'array',
        ];
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(ContentCampaign::class, 'campaign_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function topics(): HasMany
    {
        return $this->hasMany(ContentTopic::class, 'campaign_target_id');
    }

    public function contents(): HasMany
    {
        return $this->hasMany(Content::class, 'campaign_target_id');
    }
}

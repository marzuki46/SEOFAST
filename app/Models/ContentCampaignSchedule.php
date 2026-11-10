<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContentCampaignSchedule extends Model
{
    protected $fillable = [
        'campaign_id', 'content_topic_id', 'content_id', 'scheduled_at', 'status',
        'locked_at', 'attempt_count', 'error_message',
    ];

    protected function casts(): array
    {
        return ['scheduled_at' => 'datetime', 'locked_at' => 'datetime', 'attempt_count' => 'integer'];
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(ContentCampaign::class, 'campaign_id');
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(ContentTopic::class, 'content_topic_id');
    }

    public function content(): BelongsTo
    {
        return $this->belongsTo(Content::class);
    }
}

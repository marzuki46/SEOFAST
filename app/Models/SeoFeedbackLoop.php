<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeoFeedbackLoop extends Model
{
    protected $fillable = [
        'content_id',
        'crawl_priority_score',
        'last_submitted_to_indexing_api_at',
        'indexing_api_submission_count',
        'gsc_coverage_state',
        'gsc_verdict',
        'gsc_indexing_state',
        'gsc_robots_txt_state',
        'gsc_page_fetch_state',
        'gsc_last_crawl_time',
        'gsc_last_sync_at',
        'current_serp_position',
        'previous_serp_position',
        'position_change',
        'requires_ai_reoptimization',
        'reoptimization_triggered_at',
        'reoptimization_count',
        'avg_clicks_7d',
        'avg_impressions_7d',
        'avg_ctr_7d',
        'avg_position_7d',
        'last_sync_at',
    ];

    protected function casts(): array
    {
        return [
            'crawl_priority_score' => 'decimal:2',
            'indexing_api_submission_count' => 'integer',
            'gsc_last_crawl_time' => 'datetime',
            'gsc_last_sync_at' => 'datetime',
            'current_serp_position' => 'integer',
            'previous_serp_position' => 'integer',
            'position_change' => 'decimal:1',
            'requires_ai_reoptimization' => 'boolean',
            'reoptimization_triggered_at' => 'datetime',
            'reoptimization_count' => 'integer',
            'avg_clicks_7d' => 'integer',
            'avg_impressions_7d' => 'integer',
            'avg_ctr_7d' => 'decimal:4',
            'avg_position_7d' => 'decimal:2',
            'last_sync_at' => 'datetime',
            'last_submitted_to_indexing_api_at' => 'datetime',
        ];
    }

    protected $table = 'seo_feedback_loops';

    public function content(): BelongsTo
    {
        return $this->belongsTo(Content::class);
    }
}
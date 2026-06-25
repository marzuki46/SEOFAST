<?php

namespace App\Models;

use App\Models\Traits\TenantAwareTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GscUrlInspection extends Model
{
    use TenantAwareTrait;

    protected $fillable = [
        'tenant_id',
        'content_id',
        'gsc_sync_log_id',
        'verdict',
        'coverage_state',
        'robots_txt_state',
        'indexing_state',
        'page_fetch_state',
        'crawled_as_mobile',
        'last_crawl_time',
        'canonical_declared_in_page',
        'canonical_selected_by_google',
        'mobile_usability_verdict',
        'mobile_usability_issues',
        'rich_results_verdict',
        'rich_results_items',
        'raw_api_response',
        'inspected_at',
    ];

    protected function casts(): array
    {
        return [
            'crawled_as_mobile' => 'boolean',
            'last_crawl_time' => 'datetime',
            'mobile_usability_issues' => 'array',
            'rich_results_items' => 'array',
            'raw_api_response' => 'array',
            'inspected_at' => 'datetime',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function content(): BelongsTo
    {
        return $this->belongsTo(Content::class);
    }

    public function syncLog(): BelongsTo
    {
        return $this->belongsTo(GscSyncLog::class, 'gsc_sync_log_id');
    }
}

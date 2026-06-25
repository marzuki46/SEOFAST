<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SerpSnapshot extends Model
{
    protected $fillable = [
        'content_id',
        'target_keyword',
        'target_country',
        'target_device',
        'position',
        'ranking_url',
        'serp_features',
        'snapshot_date',
    ];

    protected function casts(): array
    {
        return [
            'position' => 'integer',
            'serp_features' => 'json',
            'snapshot_date' => 'date',
        ];
    }

    protected $table = 'serp_snapshots';

    public function content(): BelongsTo
    {
        return $this->belongsTo(Content::class);
    }

    public function scopeByKeyword($query, string $keyword)
    {
        return $query->where('target_keyword', $keyword);
    }

    public function scopeLatestFirst($query)
    {
        return $query->orderBy('snapshot_date', 'desc');
    }
}
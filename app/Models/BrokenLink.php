<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BrokenLink extends Model
{
    protected $fillable = [
        'content_id',
        'url',
        'url_hash',
        'anchor_text',
        'link_type',
        'status_code',
        'error',
        'is_broken',
        'checked_at',
    ];

    protected function casts(): array
    {
        return [
            'is_broken' => 'boolean',
            'status_code' => 'integer',
            'checked_at' => 'datetime',
        ];
    }

    public function content(): BelongsTo
    {
        return $this->belongsTo(Content::class);
    }

    public function scopeBroken($query)
    {
        return $query->where('is_broken', true);
    }

    public function scopeInternal($query)
    {
        return $query->where('link_type', 'internal');
    }

    public function scopeExternal($query)
    {
        return $query->where('link_type', 'external');
    }
}

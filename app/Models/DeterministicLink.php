<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeterministicLink extends Model
{
    protected $fillable = [
        'source_content_id',
        'target_content_id',
        'mandatory_anchor_text',
        'is_injected_successfully',
        'injected_at',
    ];

    protected function casts(): array
    {
        return [
            'is_injected_successfully' => 'boolean',
            'injected_at' => 'datetime',
        ];
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(Content::class, 'source_content_id');
    }

    public function target(): BelongsTo
    {
        return $this->belongsTo(Content::class, 'target_content_id');
    }
}
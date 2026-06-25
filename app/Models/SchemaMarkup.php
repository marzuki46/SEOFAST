<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchemaMarkup extends Model
{
    protected $fillable = [
        'content_id',
        'schema_type',
        'schema_payload',
        'is_validated',
        'validated_at',
        'validation_issues',
    ];

    protected function casts(): array
    {
        return [
            'schema_payload' => 'json',
            'is_validated' => 'boolean',
            'validated_at' => 'datetime',
        ];
    }

    protected $table = 'schema_markups';

    public function content(): BelongsTo
    {
        return $this->belongsTo(Content::class);
    }
}
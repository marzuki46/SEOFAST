<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentEmbedding extends Model
{
    protected $fillable = [
        'content_id',
        'chunk_text',
        'vector_data',
    ];

    protected $casts = [
        'vector_data' => 'array',
    ];

    public function content()
    {
        return $this->belongsTo(Content::class);
    }
}

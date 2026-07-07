<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageError extends Model
{
    protected $fillable = [
        'url',
        'url_hash',
        'referer',
        'count',
        'first_seen',
        'last_seen',
    ];

    protected function casts(): array
    {
        return [
            'count' => 'integer',
            'first_seen' => 'datetime',
            'last_seen' => 'datetime',
        ];
    }
}

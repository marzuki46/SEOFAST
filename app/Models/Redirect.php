<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Redirect extends Model
{
    protected $fillable = [
        'old_url',
        'new_url',
        'status_code',
        'active',
        'hits',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'status_code' => 'integer',
            'hits' => 'integer',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeMatchUrl($query, string $path)
    {
        return $query->where('old_url', $path);
    }
}

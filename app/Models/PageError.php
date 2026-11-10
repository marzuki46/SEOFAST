<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PageError extends Model
{
    protected $fillable = [
        'url',
        'url_hash',
        'status_code',
        'referer',
        'last_ip_address',
        'category',
        'last_user_agent',
        'count',
        'first_seen',
        'last_seen',
    ];

    protected function casts(): array
    {
        return [
            'count' => 'integer',
            'status_code' => 'integer',
            'first_seen' => 'datetime',
            'last_seen' => 'datetime',
        ];
    }

    public function visits(): HasMany
    {
        return $this->hasMany(PageErrorVisit::class);
    }
}

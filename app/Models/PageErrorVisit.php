<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageErrorVisit extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'page_error_id', 'ip_address', 'path', 'status_code', 'category', 'user_agent', 'referer', 'created_at',
    ];

    protected function casts(): array
    {
        return ['status_code' => 'integer', 'created_at' => 'datetime'];
    }

    public function pageError(): BelongsTo
    {
        return $this->belongsTo(PageError::class);
    }
}

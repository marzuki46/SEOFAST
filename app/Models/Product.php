<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use SoftDeletes, \App\Traits\HasSeoMeta;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image_url',
        'price',
        'features',
        'download_url',
        'download_file',
        'shortcode',
        'is_active',
    ];

    protected $casts = [
        'features'  => 'array',
        'is_active' => 'boolean',
    ];

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}

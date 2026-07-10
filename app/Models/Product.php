<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
        'development_price',
        'features',
        'download_url',
        'download_file',
        'shortcode',
        'is_active',
        'launched_at',
        'display_sections',
        'gallery_images',
        'specifications',
        'faq',
        'enable_buy_button',
        'enable_inquiry_button',
        'inquiry_label',
        'inquiry_url',
        'changelog',
        'documentation',
        'is_featured',
        'purchase_count',
    ];

    protected $casts = [
        'features'            => 'array',
        'is_active'           => 'boolean',
        'launched_at'         => 'datetime',
        'enable_buy_button'   => 'boolean',
        'enable_inquiry_button' => 'boolean',
        'display_sections'    => 'array',
        'gallery_images'      => 'array',
        'specifications'      => 'array',
        'faq'                 => 'array',
        'changelog'           => 'array',
        'is_featured'          => 'boolean',
        'purchase_count'       => 'integer',
        'development_price'    => 'integer',
    ];

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(ProductCategory::class, 'product_category_product');
    }

    public function preOrders(): HasMany
    {
        return $this->hasMany(PreOrder::class);
    }

    public function isLaunched(): bool
    {
        return !is_null($this->launched_at);
    }

    public function isDevelopment(): bool
    {
        return !is_null($this->development_price) && !$this->isLaunched();
    }

    public function displayPrice(): int
    {
        return $this->isDevelopment() ? $this->development_price : $this->price;
    }

    public function displayPriceFormatted(): string
    {
        return 'Rp ' . number_format($this->displayPrice(), 0, ',', '.');
    }

    public function originalPriceFormatted(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }
}

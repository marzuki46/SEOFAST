<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoMeta extends Model
{
    use \Spatie\Translatable\HasTranslations;

    public $translatable = [
        'title',
        'description',
        'og_title',
        'og_description',
    ];

    protected $fillable = [
        'model_type',
        'model_id',
        'title',
        'description',
        'canonical',
        'robots',
        'schema',
        'og_image',
        'og_title',
        'og_description',
        'twitter_card',
    ];

    protected $casts = [
        'schema' => 'array',
    ];

    /**
     * Get the parent model (Post, Page, Product, etc.) that owns the seo_meta.
     */
    public function model()
    {
        return $this->morphTo();
    }
}

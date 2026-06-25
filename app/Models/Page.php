<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use \App\Traits\HasSeoMeta;

    protected $fillable = [
        'title',
        'slug',
        'html_content',
        'css_content',
        'meta_title',
        'meta_description',
        'is_homepage'
    ];

    protected $casts = [
        'is_homepage' => 'boolean',
    ];
}

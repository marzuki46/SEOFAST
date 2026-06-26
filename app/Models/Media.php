<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = [
        'filename',
        'path',
        'url',
        'alt_text',
        'title',
        'description',
        'size',
        'mime_type'
    ];
}

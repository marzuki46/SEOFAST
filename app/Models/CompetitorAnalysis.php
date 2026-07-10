<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompetitorAnalysis extends Model
{
    protected $fillable = [
        'keyword',
        'status',
        'results',
        'error_message',
    ];

    protected $casts = [
        'results' => 'array',
    ];
}

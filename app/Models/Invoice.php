<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'invoice_number',
        'status',
        'invoice_date',
        'due_date',
        'paid_at',
        'payment_method',
        'payment_proof',
        'notes',
        'subtotal',
        'tax',
        'total',
    ];

    protected function casts(): array
    {
        return [
            'invoice_date' => 'date',
            'due_date'     => 'date',
            'paid_at'      => 'datetime',
            'subtotal'     => 'decimal:2',
            'tax'          => 'decimal:2',
            'total'        => 'decimal:2',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
}
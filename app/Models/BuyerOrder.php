<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BuyerOrder extends Model
{
    protected $fillable = [
        'buyer_id', 'product_id', 'order_number', 'unique_code',
        'amount', 'unique_amount', 'status', 'payment_proof',
        'payment_method', 'admin_note', 'paid_at', 'verified_at', 'verified_by',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'unique_amount' => 'decimal:2',
            'paid_at' => 'datetime',
            'verified_at' => 'datetime',
        ];
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function access()
    {
        return $this->hasOne(BuyerProductAccess::class, 'order_id');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending'  => '<span class="badge-yellow">Menunggu Pembayaran</span>',
            'paid'     => '<span class="badge-blue">Bukti Terkirim</span>',
            'verified' => '<span class="badge-green">Terverifikasi</span>',
            'rejected' => '<span class="badge-red">Ditolak</span>',
            'refunded' => '<span class="badge-gray">Refund</span>',
            default    => '<span class="badge-gray">' . $this->status . '</span>',
        };
    }

    public static function generateOrderNumber(): string
    {
        return 'ORD-' . strtoupper(substr(uniqid(), -8));
    }

    public static function generateUniqueCode(): string
    {
        $min = (int) system_setting('payment_unique_code_min', 100);
        $max = (int) system_setting('payment_unique_code_max', 999);
        return (string) rand($min, $max);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class Buyer extends Authenticatable implements MustVerifyEmail
{
    use \Illuminate\Auth\MustVerifyEmail, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'google_id',
        'name',
        'email',
        'avatar',
        'password',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function orders(): HasMany
    {
        return $this->hasMany(BuyerOrder::class);
    }

    public function productAccesses(): HasMany
    {
        return $this->hasMany(BuyerProductAccess::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'buyer_product_accesses')
            ->withPivot(['granted_at', 'expires_at', 'access_count', 'is_active'])
            ->wherePivot('is_active', true);
    }

    public function hasAccessTo(int $productId): bool
    {
        return $this->productAccesses()
            ->where('product_id', $productId)
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->exists();
    }

    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=6366f1&color=fff';
    }
}

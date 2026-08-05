<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactInquiry extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
        'source',
        'ip_address',
        'url',
        'is_spam',
        'admin_note',
        'replied_at',
        'replied_by',
    ];

    protected function casts(): array
    {
        return [
            'replied_at' => 'datetime',
            'is_spam' => 'boolean',
        ];
    }

    public function scopeSpam($query)
    {
        return $query->where('is_spam', true);
    }

    public function scopeNotSpam($query)
    {
        return $query->where('is_spam', false);
    }

    public function sourceLabel(): string
    {
        return match ($this->source) {
            'contact' => 'Form Kontak',
            'chat' => 'Live Chat',
            'enterprise' => 'Enterprise',
            default => '—',
        };
    }

    public function repliedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'replied_by');
    }

    public function statusBadge(): string
    {
        return match ($this->status) {
            'unread' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Belum Dibaca</span>',
            'read' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Sudah Dibaca</span>',
            'replied' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Sudah Dibalas</span>',
            default => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">' . $this->status . '</span>',
        };
    }
}

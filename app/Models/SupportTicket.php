<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportTicket extends Model
{
    protected $fillable = [
        'buyer_id',
        'ticket_number',
        'subject',
        'message',
        'category',
        'priority',
        'status',
        'solved_at',
        'closed_by',
        'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'solved_at' => 'datetime',
        ];
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(TicketReply::class);
    }

    public function closedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public static function generateTicketNumber(): string
    {
        $last = self::lockForUpdate()->orderBy('id', 'desc')->first();
        $num = $last ? intval(substr($last->ticket_number, 4)) + 1 : 1;
        return 'TKT-' . str_pad($num, 4, '0', STR_PAD_LEFT);
    }

    public function statusBadge(): string
    {
        return match ($this->status) {
            'open' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Open</span>',
            'wait_response' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Menunggu Respon</span>',
            'on_progress' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">On Progress</span>',
            'solved' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Solved</span>',
            'closed' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">Closed</span>',
            default => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">' . $this->status . '</span>',
        };
    }
}

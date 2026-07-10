<?php

namespace App\Mail;

use App\Models\BuyerOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderVerified extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public BuyerOrder $order
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pembayaran Diverifikasi — Akses Produk Telah Dibuka',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.orders.verified',
        );
    }
}

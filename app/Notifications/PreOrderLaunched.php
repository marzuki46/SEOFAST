<?php

namespace App\Notifications;

use App\Models\PreOrder;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PreOrderLaunched extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Product $product,
        public PreOrder $preOrder,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('🎉 ' . $this->product->name . ' — Now Available!')
            ->greeting('Hello ' . $this->preOrder->name . '!')
            ->line('Good news! **' . $this->product->name . '** is now officially launched and ready for purchase.')
            ->line('As one of our pre-order customers, you get early access. Here\'s what you need to know:')
            ->line('**Price:** Rp ' . number_format($this->product->price, 0, ',', '.'))
            ->action('Buy Now', route('products.show', $this->product->slug))
            ->line('Thank you for your interest in our product!')
            ->salutation('Best regards, ' . config('app.name'));
    }
}

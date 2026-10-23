<?php

namespace App\Notifications\User;

use App\Models\{Payment, Subscribe};
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Cobrança recorrente confirmada: assinatura renovada por mais um ciclo.
 */
class SubscriptionRenewedNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly Subscribe $subscribe,
        private readonly ?Payment $payment = null,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Assinatura renovada - Time Plus')
            ->markdown('emails.user.subscription-renewed-notification', [
                'user'      => $notifiable,
                'subscribe' => $this->subscribe,
                'plan'      => $this->subscribe->plan,
                'payment'   => $this->payment,
                'url'       => route('user.subscribe.show'),
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}

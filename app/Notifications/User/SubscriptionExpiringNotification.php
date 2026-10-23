<?php

namespace App\Notifications\User;

use App\Models\Subscribe;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Aviso de assinatura próxima do vencimento (renovação automática ou manual).
 */
class SubscriptionExpiringNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly Subscribe $subscribe,
        private readonly int $daysLeft,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $subject = $this->daysLeft <= 0
            ? 'Sua assinatura vence hoje - Time Plus'
            : "Sua assinatura vence em {$this->daysLeft} dia(s) - Time Plus";

        return (new MailMessage())
            ->subject($subject)
            ->markdown('emails.user.subscription-expiring-notification', [
                'user'      => $notifiable,
                'subscribe' => $this->subscribe,
                'plan'      => $this->subscribe->plan,
                'daysLeft'  => $this->daysLeft,
                'url'       => route('user.subscribe.show'),
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}

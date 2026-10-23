<?php

namespace App\Notifications\User;

use App\Models\{Payment, Subscribe};
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Cobrança da assinatura vencida sem pagamento: pede a regularização antes da
 * perda de acesso.
 */
class SubscriptionPaymentFailedNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly Subscribe $subscribe,
        private readonly Payment $payment,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Não conseguimos confirmar o pagamento da sua assinatura - Time Plus')
            ->markdown('emails.user.subscription-payment-failed-notification', [
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

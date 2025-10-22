<?php

namespace App\Notifications\User;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionActiveNotification extends Notification
{
    use Queueable;

    public $subscribe;
    public $payment;

    public function __construct($subscribe, $payment)
    {
        $this->subscribe = $subscribe;
        $this->payment = $payment;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
        ->subject('Assinatura Ativa - Pagamento Confirmado - Time Plus')
        ->markdown('emails.user.subscription-active-notification', [
            'user'      => $notifiable,
            'subscribe' => $this->subscribe,
            'plan'      => $this->subscribe->plan,
            'payment'   => $this->payment,
            'url'       => route('user.subscribe.show'),
        ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}

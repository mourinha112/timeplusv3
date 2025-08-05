<?php

namespace App\Notifications\User;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
        ->subject('Bem-vindo(a) à Time Plus!')
        ->markdown('emails.user.welcome-notification', [
            'user' => $notifiable,
            'url' => route('user.auth.login')
        ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}

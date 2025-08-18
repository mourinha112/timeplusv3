<?php

namespace App\Notifications\Specialist;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetNotification extends Notification
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
            ->subject('Redefinição de Senha')
            ->markdown('emails.specialist.password-reset-notification', [
                'specialist' => $notifiable,
                'url'        => route('specialist.auth.login'),
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}

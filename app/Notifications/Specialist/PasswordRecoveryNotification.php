<?php

namespace App\Notifications\Specialist;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordRecoveryNotification extends Notification
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
            ->subject('Recuperação de Senha')
            ->markdown('emails.specialist.password-recovery-notification', [
                'specialist' => $notifiable,
                'url'  => route('specialist.auth.password.reset', ['token' => $notifiable->recovery_password_token]),
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}

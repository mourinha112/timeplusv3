<?php

namespace App\Notifications\Company;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordRecoveryNotification extends Notification
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Recuperação de Senha')
            ->markdown('emails.company.password-recovery-notification', [
                'company' => $notifiable,
                'url'     => route('company.auth.password.reset', ['token' => $notifiable->recovery_password_token]),
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}

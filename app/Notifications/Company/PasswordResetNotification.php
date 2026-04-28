<?php

namespace App\Notifications\Company;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetNotification extends Notification
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Senha redefinida com sucesso')
            ->markdown('emails.company.password-reset-notification', [
                'company' => $notifiable,
                'url'     => route('company.auth.login'),
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}

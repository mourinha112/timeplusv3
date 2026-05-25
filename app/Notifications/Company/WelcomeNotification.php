<?php

namespace App\Notifications\Company;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification
{
    public function __construct(private readonly string $temporaryPassword) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Bem-vindo(a) ao Time Plus')
            ->greeting("Olá, {$notifiable->name}!")
            ->line('Sua conta de empresa foi criada na plataforma Time Plus.')
            ->line("E-mail de acesso: {$notifiable->email}")
            ->line("Senha temporária: {$this->temporaryPassword}")
            ->action('Acessar painel da empresa', route('company.auth.login'))
            ->line('Recomendamos alterar a senha após o primeiro acesso.');
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}

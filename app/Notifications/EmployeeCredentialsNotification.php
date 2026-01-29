<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmployeeCredentialsNotification extends Notification
{

    public function __construct(
        public string $companyName,
        public string $email,
        public string $password
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $loginUrl = route('user.auth.login');

        return (new MailMessage)
            ->subject('Bem-vindo ao Time Plus - Suas Credenciais de Acesso')
            ->greeting("Olá, {$notifiable->name}!")
            ->line("Você foi cadastrado como funcionário da empresa **{$this->companyName}** na plataforma Time Plus.")
            ->line('Suas credenciais de acesso são:')
            ->line("**E-mail:** {$this->email}")
            ->line("**Senha temporária:** {$this->password}")
            ->action('Acessar Plataforma', $loginUrl)
            ->line('Recomendamos que você altere sua senha após o primeiro acesso.')
            ->line('---')
            ->line('Se você não esperava receber este e-mail, por favor ignore-o.')
            ->salutation('Atenciosamente, Equipe Time Plus');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'company_name' => $this->companyName,
            'email'        => $this->email,
        ];
    }
}

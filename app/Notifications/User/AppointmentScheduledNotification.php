<?php

namespace App\Notifications\User;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentScheduledNotification extends Notification
{
    use Queueable;

    public $appointment;

    public function __construct($appointment)
    {
        $this->appointment = $appointment;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
        ->subject('Sessão Agendada com Sucesso - Time Plus')
        ->markdown('emails.user.appointment-scheduled-notification', [
            'user'        => $notifiable,
            'appointment' => $this->appointment,
            'specialist'  => $this->appointment->specialist,
            'url'         => route('user.appointment.payment', ['appointment_id' => $this->appointment->id]),
        ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}

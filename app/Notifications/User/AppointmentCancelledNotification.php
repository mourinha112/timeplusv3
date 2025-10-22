<?php

namespace App\Notifications\User;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentCancelledNotification extends Notification
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
        ->subject('Agendamento Cancelado - Time Plus')
        ->markdown('emails.user.appointment-cancelled-notification', [
            'user'        => $notifiable,
            'appointment' => $this->appointment,
            'specialist'  => $this->appointment->specialist,
            'url'         => route('user.appointment.index'),
        ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}

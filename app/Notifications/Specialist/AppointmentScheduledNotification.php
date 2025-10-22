<?php

namespace App\Notifications\Specialist;

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
        ->subject('Novo Agendamento Recebido - Time Plus')
        ->markdown('emails.specialist.appointment-scheduled-notification', [
            'specialist'  => $notifiable,
            'appointment' => $this->appointment,
            'user'        => $this->appointment->user,
            'url'         => route('specialist.appointment.index'),
        ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}

<?php

namespace App\Notifications\Specialist;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentReminderNotification extends Notification
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
        $date = \Carbon\Carbon::parse($this->appointment->appointment_date)->format('d/m/Y');
        $time = \Carbon\Carbon::parse($this->appointment->appointment_time)->format('H:i');

        return (new MailMessage())
            ->subject("Lembrete: Você tem uma sessão hoje às {$time} - Time Plus")
            ->markdown('emails.specialist.appointment-reminder-notification', [
                'specialist'  => $notifiable,
                'appointment' => $this->appointment,
                'user'        => $this->appointment->user,
                'date'        => $date,
                'time'        => $time,
                'url'         => route('specialist.appointment.index'),
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}

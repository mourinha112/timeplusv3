<?php

namespace App\Notifications\User;

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
            ->subject("Lembrete: Sua sessão é hoje às {$time} - Time Plus")
            ->markdown('emails.user.appointment-reminder-notification', [
                'user'        => $notifiable,
                'appointment' => $this->appointment,
                'specialist'  => $this->appointment->specialist,
                'date'        => $date,
                'time'        => $time,
                'url'         => route('user.appointment.index'),
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}

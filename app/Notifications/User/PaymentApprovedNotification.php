<?php

namespace App\Notifications\User;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentApprovedNotification extends Notification
{
    use Queueable;

    public $appointment;
    public $payment;

    public function __construct($appointment, $payment)
    {
        $this->appointment = $appointment;
        $this->payment = $payment;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
        ->subject('Pagamento Aprovado - Sessão Confirmada - Time Plus')
        ->markdown('emails.user.payment-approved-notification', [
            'user'        => $notifiable,
            'appointment' => $this->appointment,
            'payment'     => $this->payment,
            'specialist'  => $this->appointment->specialist,
            'room'        => $this->appointment->room,
            'url'         => $this->appointment->room ? route('user.videocall.show', ['code' => $this->appointment->room->code]) : route('user.appointment.index'),
        ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}

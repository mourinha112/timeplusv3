<?php

namespace App\Notifications\Company;

use App\Models\{CompanyCreditBalance, Payment};
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Compra de créditos extras confirmada: créditos liberados (validade de 6 meses).
 */
class ExtraCreditsGrantedNotification extends Notification
{
    public function __construct(
        private readonly Payment $payment,
        private readonly CompanyCreditBalance $balance,
        private readonly ?int $totalBalance = null,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $credits = (int) $this->balance->credits_total;

        return (new MailMessage())
            ->subject("{$credits} crédito(s) liberado(s) - Time Plus")
            ->markdown('emails.company.extra-credits-granted-notification', [
                'company'      => $notifiable,
                'payment'      => $this->payment,
                'balance'      => $this->balance,
                'credits'      => $credits,
                'totalBalance' => $this->totalBalance,
                'url'          => route('company.credit.index'),
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}

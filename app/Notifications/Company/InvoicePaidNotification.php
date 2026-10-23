<?php

namespace App\Notifications\Company;

use App\Models\{CompanyPlan, Payment};
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Fatura mensal da empresa confirmada: plano renovado para o período e, no
 * modelo de pacote de créditos, créditos do mês liberados.
 */
class InvoicePaidNotification extends Notification
{
    public function __construct(
        private readonly Payment $invoice,
        private readonly ?CompanyPlan $plan = null,
        private readonly ?int $creditBalance = null,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $period = $this->invoice->metadata['period'] ?? now()->format('Y-m');

        return (new MailMessage())
            ->subject("Pagamento confirmado - plano renovado ({$period}) - Time Plus")
            ->markdown('emails.company.invoice-paid-notification', [
                'company'       => $notifiable,
                'invoice'       => $this->invoice,
                'plan'          => $this->plan,
                'period'        => $period,
                'creditBalance' => $this->creditBalance,
                'url'           => route('company.payment.show', ['payment' => $this->invoice->id]),
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}

<?php

namespace App\Notifications\Company;

use App\Models\{CompanyPlan, Payment};
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Fatura mensal (renovação) do plano da empresa gerada: envia o PIX copia e cola.
 */
class MonthlyInvoiceGeneratedNotification extends Notification
{
    public function __construct(
        private readonly CompanyPlan $plan,
        private readonly Payment $invoice,
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
            ->subject("Fatura mensal {$period} disponível - Time Plus")
            ->markdown('emails.company.monthly-invoice-generated-notification', [
                'company'         => $notifiable,
                'plan'            => $this->plan,
                'invoice'         => $this->invoice,
                'period'          => $period,
                'activeEmployees' => $this->plan->isPerEmployee() ? $this->plan->getActiveUsersCount() : null,
                'url'             => route('company.payment.show', ['payment' => $this->invoice->id]),
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}

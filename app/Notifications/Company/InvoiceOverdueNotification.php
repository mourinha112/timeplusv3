<?php

namespace App\Notifications\Company;

use App\Models\Payment;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Fatura da empresa vencida sem pagamento.
 */
class InvoiceOverdueNotification extends Notification
{
    public function __construct(private readonly Payment $invoice)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $period = $this->invoice->metadata['period'] ?? null;

        return (new MailMessage())
            ->subject('Fatura vencida - regularize seu plano - Time Plus')
            ->markdown('emails.company.invoice-overdue-notification', [
                'company' => $notifiable,
                'invoice' => $this->invoice,
                'period'  => $period,
                'url'     => route('company.payment.show', ['payment' => $this->invoice->id]),
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}

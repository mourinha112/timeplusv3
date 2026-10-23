<?php

namespace App\Notifications\Company;

use App\Models\{CompanyPlan, Payment};
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Plano da empresa contratado/ativado. Quando a 1ª fatura PIX é gerada junto,
 * o e-mail já traz o código copia e cola para pagamento.
 */
class PlanContractedNotification extends Notification
{
    public function __construct(
        private readonly CompanyPlan $plan,
        private readonly ?Payment $invoice = null,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject("Plano \"{$this->plan->name}\" ativado - Time Plus")
            ->markdown('emails.company.plan-contracted-notification', [
                'company' => $notifiable,
                'plan'    => $this->plan,
                'invoice' => $this->invoice,
                'url'     => route('company.plan.index'),
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}

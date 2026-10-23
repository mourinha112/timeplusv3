@component('mail::message')
# Pagamento confirmado!

Olá, {{ $company->name }}! Recebemos o pagamento da fatura de **{{ $period }}**.

**Detalhes:**
- **Valor pago:** R$ {{ number_format((float) $invoice->amount, 2, ',', '.') }}
- **Pago em:** {{ optional($invoice->paid_at)->format('d/m/Y H:i') ?? now()->format('d/m/Y H:i') }}
@if($plan)
- **Plano:** {{ $plan->name }}
@if($plan->isCreditPack())
- **Créditos do mês:** {{ $plan->monthly_credits }} sessão(ões) liberadas
@endif
@endif
@if($creditBalance !== null)
- **Saldo total de créditos:** {{ $creditBalance }} sessão(ões)
@endif

Seu plano segue ativo e os funcionários podem continuar agendando normalmente.

@component('mail::button', ['url' => $url])
Ver comprovante
@endcomponent

Obrigado por confiar na Time Plus!<br>
{{ config('app.name') }}
@endcomponent

@component('mail::message')
# Assinatura renovada, {{ $user->name }}!

Recebemos o pagamento da renovação e sua assinatura continua ativa.

**Detalhes da assinatura:**
- **Plano:** {{ $plan->name }}
@if($payment)
- **Valor pago:** R$ {{ number_format((float) $payment->amount, 2, ',', '.') }}
@endif
@if($subscribe->end_date)
- **Válida até:** {{ \Carbon\Carbon::parse($subscribe->end_date)->format('d/m/Y') }}
@endif
@if($subscribe->next_billing_date)
- **Próxima cobrança:** {{ \Carbon\Carbon::parse($subscribe->next_billing_date)->format('d/m/Y') }}
@endif

Nada muda para você: pode continuar agendando suas sessões normalmente.

@component('mail::button', ['url' => $url])
Ver minha assinatura
@endcomponent

Obrigado por continuar com a gente!<br>
{{ config('app.name') }}
@endcomponent

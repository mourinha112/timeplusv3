@component('mail::message')
# Pagamento pendente, {{ $user->name }}

Não conseguimos confirmar o pagamento da sua assinatura do plano **{{ $plan->name }}**.

**Detalhes:**
- **Plano:** {{ $plan->name }}
- **Valor:** R$ {{ number_format((float) $payment->amount, 2, ',', '.') }}
@if($subscribe->end_date)
- **Acesso válido até:** {{ \Carbon\Carbon::parse($subscribe->end_date)->format('d/m/Y') }}
@endif

@if($payment->pix_key)
Você pode regularizar agora com o PIX copia e cola abaixo:

```
{{ $payment->pix_key }}
```
@endif

@component('mail::button', ['url' => $url])
Regularizar assinatura
@endcomponent

Se o pagamento já foi feito, desconsidere este aviso — a confirmação pode levar alguns minutos.<br>
{{ config('app.name') }}
@endcomponent

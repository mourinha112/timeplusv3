@component('mail::message')
# Fatura vencida

Olá, {{ $company->name }}. A fatura{{ $period ? " referente a {$period}" : '' }} venceu e ainda não identificamos o pagamento.

**Detalhes:**
- **Valor:** R$ {{ number_format((float) $invoice->amount, 2, ',', '.') }}
- **Descrição:** {{ $invoice->description }}

@if($invoice->pix_key)
Você pode regularizar agora com o PIX copia e cola abaixo:

```
{{ $invoice->pix_key }}
```
@endif

@component('mail::button', ['url' => $url])
Regularizar pagamento
@endcomponent

Se o pagamento já foi feito, desconsidere este aviso — a confirmação pode levar alguns minutos.<br>
{{ config('app.name') }}
@endcomponent

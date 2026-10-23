@component('mail::message')
# Fatura de {{ $period }} disponível

Olá, {{ $company->name }}! A fatura mensal do plano **{{ $plan->name }}** já está disponível.

**Resumo da fatura:**
- **Período:** {{ $period }}
- **Valor:** R$ {{ number_format((float) $invoice->amount, 2, ',', '.') }}
- **Descrição:** {{ $invoice->description }}
@if($plan->isCreditPack())
- **Créditos do mês:** {{ $plan->monthly_credits }} sessão(ões), válidos até o último dia do mês
@elseif($activeEmployees !== null)
- **Funcionários ativos:** {{ $activeEmployees }}
@endif

@if($invoice->pix_key)
Pague com o PIX copia e cola abaixo:

```
{{ $invoice->pix_key }}
```
@endif

@component('mail::button', ['url' => $url])
Ver fatura no painel
@endcomponent

Assim que o pagamento for confirmado, o plano é renovado automaticamente.<br>
{{ config('app.name') }}
@endcomponent

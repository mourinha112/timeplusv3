@component('mail::message')
# Plano ativado, {{ $company->name }}!

O plano **{{ $plan->name }}** foi ativado para a sua empresa na Time Plus.

**Detalhes do plano:**
- **Modelo de cobrança:** {{ $plan->isCreditPack() ? 'Pacote de créditos' : 'Por funcionário ativo' }}
@if($plan->isCreditPack())
- **Créditos mensais:** {{ $plan->monthly_credits }} sessão(ões) por mês
@if($plan->credits_per_employee)
- **Limite por funcionário:** {{ $plan->credits_per_employee }} sessão(ões) por mês
@endif
- **Valor por sessão:** R$ {{ number_format((float) \App\Models\CompanyPlan::CREDIT_UNIT_PRICE, 2, ',', '.') }}
@else
- **Valor por funcionário ativo:** R$ {{ number_format($plan->currentPerEmployeePrice(), 2, ',', '.') }} no 1º mês e R$ {{ number_format((float) \App\Models\CompanyPlan::PER_EMPLOYEE_RECURRING_PRICE, 2, ',', '.') }} nos meses seguintes
@endif
- **Contratado em:** {{ \Carbon\Carbon::parse($plan->contracted_at ?? $plan->created_at)->format('d/m/Y') }}

@if($invoice)
**Fatura do 1º mês:** R$ {{ number_format((float) $invoice->amount, 2, ',', '.') }}

@if($invoice->pix_key)
Pague com o PIX copia e cola abaixo:

```
{{ $invoice->pix_key }}
```
@endif

A fatura também fica disponível no painel da empresa, em **Pagamentos**.
@else
A primeira fatura será gerada automaticamente assim que houver funcionários ativos no plano.
@endif

@component('mail::button', ['url' => $url])
Ver plano da empresa
@endcomponent

Qualquer dúvida, é só responder este e-mail.<br>
{{ config('app.name') }}
@endcomponent

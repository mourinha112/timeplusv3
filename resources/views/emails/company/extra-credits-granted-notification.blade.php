@component('mail::message')
# {{ $credits }} crédito(s) liberado(s)!

Olá, {{ $company->name }}! O pagamento da compra de créditos extras foi confirmado e os créditos já estão disponíveis.

**Detalhes da compra:**
- **Créditos adquiridos:** {{ $credits }} sessão(ões)
- **Valor pago:** R$ {{ number_format((float) $payment->amount, 2, ',', '.') }}
- **Válidos até:** {{ \Carbon\Carbon::parse($balance->valid_until)->format('d/m/Y') }}
@if($totalBalance !== null)
- **Saldo total disponível:** {{ $totalBalance }} sessão(ões)
@endif

Os créditos mensais são consumidos primeiro; os extras só são usados depois que o saldo do mês acaba.

@component('mail::button', ['url' => $url])
Ver saldo de créditos
@endcomponent

{{ config('app.name') }}
@endcomponent

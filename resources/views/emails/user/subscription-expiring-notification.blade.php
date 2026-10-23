@component('mail::message')
# {{ $daysLeft <= 0 ? 'Sua assinatura vence hoje' : 'Sua assinatura está perto de vencer' }}

Olá, {{ $user->name }}!

@if($daysLeft <= 0)
A sua assinatura do plano **{{ $plan->name }}** vence hoje.
@else
A sua assinatura do plano **{{ $plan->name }}** vence em **{{ $daysLeft }} dia(s)**.
@endif

**Detalhes:**
- **Plano:** {{ $plan->name }}
@if($subscribe->end_date)
- **Vencimento:** {{ \Carbon\Carbon::parse($subscribe->end_date)->format('d/m/Y') }}
@endif

@if($subscribe->gateway_subscription_id)
A renovação é automática — basta manter o pagamento em dia para não perder o acesso.
@else
Renove pelo painel para continuar agendando suas sessões sem interrupção.
@endif

@component('mail::button', ['url' => $url])
Ver minha assinatura
@endcomponent

{{ config('app.name') }}
@endcomponent

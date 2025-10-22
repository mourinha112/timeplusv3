@component('mail::message')
# Parabéns, {{ $user->name }}!

Seu pagamento foi confirmado e sua assinatura está ativa!

**Detalhes da Assinatura:**
- **Plano:** {{ $plan->name }}
- **Valor:** R$ {{ number_format($payment->amount / 100, 2, ',', '.') }}
- **Início:** {{ \Carbon\Carbon::parse($subscribe->start_date)->format('d/m/Y') }}
@if($subscribe->end_date)
- **Término:** {{ \Carbon\Carbon::parse($subscribe->end_date)->format('d/m/Y') }}
@endif

Agora você pode aproveitar todos os benefícios do seu plano!

@component('mail::button', ['url' => $url])
Ver Minha Assinatura
@endcomponent

Se tiver alguma dúvida sobre os benefícios do seu plano, entre em contato conosco.

Obrigado por assinar!<br>
{{ config('app.name') }}
@endcomponent

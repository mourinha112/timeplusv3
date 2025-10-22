@component('mail::message')
# Parabéns, {{ $user->name }}!

Seu pagamento foi aprovado e sua sessão está confirmada!

**Detalhes da Sessão:**
- **Especialista:** {{ $specialist->name }}
- **Data:** {{ \Carbon\Carbon::parse($appointment->schedule_date)->format('d/m/Y') }}
- **Horário:** {{ \Carbon\Carbon::parse($appointment->schedule_date)->format('H:i') }}
- **Valor Pago:** R$ {{ number_format($payment->amount / 100, 2, ',', '.') }}

@if($room)
**Sua sala de vídeo está pronta!**

@component('mail::button', ['url' => $url])
Acessar Sala de Vídeo
@endcomponent
@else
Você receberá o link da sala de vídeo em breve.

@component('mail::button', ['url' => $url])
Ver Meus Agendamentos
@endcomponent
@endif

Aguardamos você na data e horário agendados!

Atenciosamente,<br>
{{ config('app.name') }}
@endcomponent

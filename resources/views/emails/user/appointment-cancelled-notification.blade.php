@component('mail::message')
# Olá, {{ $user->name }}

Informamos que seu agendamento foi cancelado.

**Detalhes do Agendamento Cancelado:**
- **Especialista:** {{ $specialist->name }}
- **Data:** {{ \Carbon\Carbon::parse($appointment->schedule_date)->format('d/m/Y') }}
- **Horário:** {{ \Carbon\Carbon::parse($appointment->schedule_date)->format('H:i') }}

@if($appointment->payment && $appointment->payment->status === 'paid')
Caso você tenha realizado o pagamento, o reembolso será processado em até 5 dias úteis.
@endif

Você pode agendar uma nova sessão quando desejar.

@component('mail::button', ['url' => $url])
Ver Agendamentos
@endcomponent

Se tiver alguma dúvida, estamos à disposição.

Atenciosamente,<br>
{{ config('app.name') }}
@endcomponent

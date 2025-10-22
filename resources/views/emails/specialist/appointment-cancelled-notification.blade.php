@component('mail::message')
# Olá, {{ $specialist->name }}

Informamos que um agendamento foi cancelado.

**Detalhes do Agendamento Cancelado:**
- **Cliente:** {{ $user->name }}
- **Data:** {{ \Carbon\Carbon::parse($appointment->schedule_date)->format('d/m/Y') }}
- **Horário:** {{ \Carbon\Carbon::parse($appointment->schedule_date)->format('H:i') }}

Este horário agora está disponível para novos agendamentos.

@component('mail::button', ['url' => $url])
Ver Agendamentos
@endcomponent

Se tiver alguma dúvida, estamos à disposição.

Atenciosamente,<br>
{{ config('app.name') }}
@endcomponent

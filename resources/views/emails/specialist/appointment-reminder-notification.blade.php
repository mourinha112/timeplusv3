@component('mail::message')
# Olá, {{ $specialist->name }}!

Este é um lembrete da sua sessão de hoje.

**Detalhes da Sessão:**
- **Paciente:** {{ $user->name }}
- **Data:** {{ $date }}
- **Horário:** {{ $time }}

A sala será aberta automaticamente 10 minutos antes do horário agendado.

@component('mail::button', ['url' => $url])
Ver Meus Agendamentos
@endcomponent

Atenciosamente,<br>
{{ config('app.name') }}
@endcomponent

@component('mail::message')
# Olá, {{ $user->name }}!

Este é um lembrete da sua sessão de hoje.

**Detalhes da Sessão:**
- **Especialista:** {{ $specialist->name }}
- **Data:** {{ $date }}
- **Horário:** {{ $time }}

Certifique-se de estar em um ambiente tranquilo e com boa conexão de internet. A sala será aberta 10 minutos antes do horário.

@component('mail::button', ['url' => $url])
Ver Meus Agendamentos
@endcomponent

Se precisar de ajuda, estamos à disposição.

Atenciosamente,<br>
{{ config('app.name') }}
@endcomponent

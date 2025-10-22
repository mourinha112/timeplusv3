@component('mail::message')
# Olá, {{ $specialist->name }}!

Você recebeu um novo agendamento!

**Detalhes do Agendamento:**
- **Cliente:** {{ $user->name }}
- **E-mail:** {{ $user->email }}
- **Data:** {{ \Carbon\Carbon::parse($appointment->schedule_date)->format('d/m/Y') }}
- **Horário:** {{ \Carbon\Carbon::parse($appointment->schedule_date)->format('H:i') }}

O cliente receberá o link da sala de vídeo assim que o pagamento for confirmado.

@component('mail::button', ['url' => $url])
Ver Agendamentos
@endcomponent

Prepare-se para oferecer uma excelente sessão!

Atenciosamente,<br>
{{ config('app.name') }}
@endcomponent

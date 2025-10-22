@component('mail::message')
# Olá, {{ $user->name }}!

Sua sessão foi agendada com sucesso!

**Detalhes do Agendamento:**
- **Especialista:** {{ $specialist->name }}
- **Data:** {{ \Carbon\Carbon::parse($appointment->schedule_date)->format('d/m/Y') }}
- **Horário:** {{ \Carbon\Carbon::parse($appointment->schedule_date)->format('H:i') }}

Para confirmar sua sessão, é necessário realizar o pagamento.

@component('mail::button', ['url' => $url])
Realizar Pagamento
@endcomponent

Após a confirmação do pagamento, você receberá o link de acesso à sala de vídeo.

Se tiver alguma dúvida, não hesite em nos contatar.

Atenciosamente,<br>
{{ config('app.name') }}
@endcomponent

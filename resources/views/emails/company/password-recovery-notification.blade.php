@component('mail::message')
# Olá, {{ $company->name }}

Recebemos um pedido para redefinir a senha de acesso da sua empresa.

Clique no botão abaixo para criar uma nova senha:

@component('mail::button', ['url' => $url])
Redefinir Senha
@endcomponent

Se você não solicitou isso, pode ignorar esta mensagem.

Atenciosamente,<br>
Equipe {{ config('app.name') }}
@endcomponent

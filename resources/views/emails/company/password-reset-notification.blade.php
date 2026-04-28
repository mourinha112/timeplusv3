@component('mail::message')
# Olá, {{ $company->name }}

A senha de acesso da sua empresa foi redefinida com sucesso.

Clique no botão abaixo para fazer login:

@component('mail::button', ['url' => $url])
Fazer Login
@endcomponent

Atenciosamente,<br>
Equipe {{ config('app.name') }}
@endcomponent

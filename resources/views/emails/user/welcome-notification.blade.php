@component('mail::message')
# Seja bem-vindo(a), {{ $user->name }}!

Estamos muito felizes em ter você conosco.

Aqui você poderá aproveitar todos os nossos recursos e benefícios exclusivos.

@component('mail::button', ['url' => $url])
Acessar minha conta
@endcomponent

Se tiver alguma dúvida, não hesite em nos contatar.

Obrigado por escolher a gente!<br>
{{ config('app.name') }}
@endcomponent
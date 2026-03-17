@component('mail::message')
# Redefinição de Senha

Você está recebendo este e-mail porque recebemos uma solicitação de redefinição de senha para sua conta.

Seu código de redefinição de senha é: **{{ $token }}**

Este código expirará em 60 minutos.

Se você não solicitou uma redefinição de senha, nenhuma ação adicional é necessária.

Obrigado,
{{ config('app.name') }}
@endcomponent

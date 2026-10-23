# E-mails do TimePlus (Resend)

Provedor de envio: **Resend**, via o transporte nativo do Laravel 12
(`config/mail.php` → mailer `resend`, credencial em `config/services.php` →
`services.resend.key`).

## 1. Instalação

```bash
composer require resend/resend-php
```

## 2. Configuração (.env)

```env
MAIL_MAILER=resend
MAIL_FROM_ADDRESS=contato@timeplus.com.br
MAIL_FROM_NAME="${APP_NAME}"
RESEND_KEY=re_xxxxxxxxxxxxxxxxxxxx
```

- A chave é criada em https://resend.com/api-keys (permissão de envio).
- O domínio de `MAIL_FROM_ADDRESS` precisa estar **verificado** no Resend
  (registros SPF/DKIM no DNS), senão o envio é recusado.
- Em desenvolvimento, use `MAIL_MAILER=log` para não enviar de verdade
  (as mensagens caem em `storage/logs/laravel.log`).
- Depois de alterar o `.env` no VPS: `php artisan config:clear`.

O SendGrid continua instalado como alternativa (`MAIL_MAILER=sendgrid` +
`SENDGRID_API_KEY`), mas o padrão agora é o Resend.

## 2.1 Registros DNS do domínio timeplus.com.br (painel do Resend)

A zona DNS de `timeplus.com.br` **não** fica no Registro.br nem na GoDaddy: os
nameservers são `ns1/ns2.mercadatadesenvolvimento.com.br` (cPanel/WHM da
Mercadata, IP 192.185.217.145). Os registros abaixo precisam ser criados lá
(Zone Editor). Depois, clicar em "Verify DNS Records" em
https://resend.com/domains.

| Tipo | Nome (host) | Valor | TTL |
| --- | --- | --- | --- |
| TXT | `resend._domainkey` | `p=MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCpP/hLBYY3K1s/ybfgJHwO0NkpyTkti9xXtupSDC+Mm/W0hYbuOHPkwi2dqVSe+f3n2XFpd8TY1ga9L4Pg4CUXaTq64QtWNGTNvqg5u6JhDut8uQa1IcZmmMvBXcfjDI4uJSdg/f5XRSYhb4SqspFtwFNYEs5aQNkxkNbaI0dEYQIDAQAB` | Auto |
| CNAME | `rsend` | `rsend-sae1.forge.rmta.net` | Auto |
| CNAME | `send` | `send.forge.rmta.net` | Auto |

Nenhum registro existente (A do site, MX `mail.timeplus.com.br`, SPF da raiz,
DMARC) precisa ser alterado. Região do domínio no Resend: São Paulo (sa-east-1).

## 2.2 Migração da zona para o DNS do Registro.br (zona completa)

Levantamento feito em 2026-10-23 a partir dos nameservers da Mercadata. Ao
ativar "Utilizar DNS do Registro.br" em registro.br → Domínios →
timeplus.com.br → "Alterar servidores DNS", recriar **todos** os registros
abaixo antes de confirmar. Hoje só `v3.timeplus.com.br` está em uso pelo
sistema, mas o restante mantém site antigo, e-mail e Google funcionando.

| Tipo | Nome | Valor |
| --- | --- | --- |
| A | `@` (raiz) | `82.25.67.8` |
| CNAME | `www` | `timeplus.com.br` |
| A | `v3` | `72.167.226.76` (VPS do sistema atual) |
| A | `app` | `208.109.175.198` |
| A | `mail` | `192.185.217.157` (HostGator, e-mail) |
| A | `*` (curinga) | `192.185.217.157` (todo subdomínio não listado resolve aqui hoje) |
| MX | `@` | prioridade `0` → `mail.timeplus.com.br` |
| TXT | `@` | `v=spf1 a mx include:websitewelcome.com include:_spf.google.com ~all` |
| TXT | `@` | `google-site-verification=adtD7ScRxZ-8sJN7YF-ESXHDbD81VQvFpkPiDbHAEWI` |
| TXT | `_dmarc` | `v=DMARC1;p=none;` |
| TXT | `default._domainkey` | `v=DKIM1; k=rsa; p=MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAvxN13sz7ortmXYfl+rIVCpGsPLAm4JLVBV1Ebe57nXKltLy7FK/YPjF859Rnk5IRsOl4ZFGa43Sm0JrHvPMfz3zMEmj15gycP9cuHZeiVlP3OeAC4Mr2huBq0zvcILJgzfUxYSH2n1FLyQrhZ/RVxznPYhH2HEZRG1ApxzB47J28dJfb1+Hqqy0/FT1Ur+AiurJ1/SInP5ppa7SZfmS4SPECPTzgjj0qQyxngT6fmhbCMLPc5YrwFphXkV159Jummz3cbHaOur2TcyIj7q4RULA3vJmuoJTk1N1NbaXtoH5I6aruynz/FZX/FWG54mSSUy3q1Ixq94l/s4VV/bARRwIDAQAB;` |
| TXT | `google._domainkey` | `v=DKIM1; k=rsa; p=MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAqXTtYl7iAshuUFshsq0MM2Ul3tdM9InI5A46KUnqgvJYaMi55qoU+ZIjj71L1y5iD06UVifiGFLijB/IOCOA3t7rDVsAhOxeoVeWyVZ7CVl4LgspETvvoFPseOiUaqoA4MrkNcHy6mgI/Sy7v/FQby4jz9lyBhxk5sTMAkVcPf9HraC2NhUBvdY8+2o06HZFr4/xk+zGqyj70puU9SCsOiiEK3TmvGmXAMovFbIk9KFkjXBZHlYz0Uw/gkYOF0y5Lf3YdeYDb+gUXAT4twSa71rWdXMPM+xxbxesLzEfBw5Kx/idkkwDvTULG8CGpC/oJUajrQRB+qlhD5U2QBWUFwIDAQAB` |
| TXT | `resend._domainkey` | (ver seção 2.1) |
| CNAME | `rsend` | `rsend-sae1.forge.rmta.net` |
| CNAME | `send` | `send.forge.rmta.net` |

Não há registro AAAA. Após publicar, aguardar a propagação (TTL atual de 1 dia
na zona antiga) e clicar "Verify DNS Records" no Resend.

## 3. Teste de envio

```bash
php artisan mail:test seu-email@exemplo.com
```

Mostra o mailer e o remetente em uso e acusa se a `RESEND_KEY` não estiver
definida.

## 4. E-mails enviados pelo sistema

### Empresa (`App\Notifications\Company`)

| E-mail | Quando dispara |
| --- | --- |
| `WelcomeNotification` | Master cadastra a empresa (envia senha temporária) |
| `PlanContractedNotification` | Master cria/ativa o plano da empresa (inclui o PIX da 1ª fatura) |
| `MonthlyInvoiceGeneratedNotification` | `company:monthly-billing` gera a fatura do mês (renovação) |
| `InvoicePaidNotification` | Webhook Asaas confirma a fatura mensal (plano renovado) |
| `ExtraCreditsGrantedNotification` | Webhook Asaas confirma a compra de créditos extras |
| `InvoiceOverdueNotification` | Webhook Asaas marca a fatura como vencida |
| `PasswordRecoveryNotification` / `PasswordResetNotification` | Fluxo de senha |

### Usuário (`App\Notifications\User`)

| E-mail | Quando dispara |
| --- | --- |
| `WelcomeNotification` | Cadastro do usuário |
| `SubscriptionActiveNotification` | 1º pagamento da assinatura confirmado (ativação) |
| `SubscriptionRenewedNotification` | Cobrança recorrente confirmada (renovação) |
| `SubscriptionExpiringNotification` | `subscriptions:notify-expiring`, a 7, 3 e 0 dias do vencimento |
| `SubscriptionPaymentFailedNotification` | Cobrança da assinatura vencida sem pagamento |
| `PaymentApprovedNotification` | Pagamento de sessão confirmado |
| `AppointmentScheduledNotification` / `AppointmentReminderNotification` / `AppointmentCancelledNotification` | Ciclo do agendamento |
| `EmployeeCredentialsNotification` | Empresa cadastra/importa funcionário |

### Especialista (`App\Notifications\Specialist`)

Boas-vindas, fluxo de senha e ciclo do agendamento (agendada / lembrete / cancelada).

## 5. Agendamentos relacionados (`routes/console.php`)

```
company:monthly-billing          -> dia 1º às 06:00 (fatura + créditos do mês + e-mail)
subscriptions:notify-expiring    -> diariamente às 09:00
appointments:send-reminders      -> a cada 5 minutos
```

O `scheduler` precisa estar ativo no VPS (`* * * * * php artisan schedule:run`),
senão nenhuma fatura mensal nem aviso de vencimento é enviado.

## 6. Observação sobre fila

As notificações são enviadas de forma **síncrona** (não implementam
`ShouldQueue`), porque o projeto não tem worker de fila rodando. Todos os envios
estão dentro de `try/catch` com log, então uma falha de e-mail nunca quebra o
webhook nem o fluxo de cobrança. Se um worker for configurado
(`QUEUE_CONNECTION=database` + `php artisan queue:work`), basta adicionar
`implements ShouldQueue` às notificações para tirar o envio do caminho crítico.

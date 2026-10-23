<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

/**
 * Verificação rápida do transporte de e-mail (Resend): envia uma mensagem real
 * e mostra o mailer/remetente em uso.
 */
class MailTestCommand extends Command
{
    protected $signature = 'mail:test {email : Destinatário do e-mail de teste}';

    protected $description = 'Envia um e-mail de teste para validar a configuração do provedor (Resend)';

    public function handle(): int
    {
        $to     = $this->argument('email');
        $mailer = config('mail.default');
        $from   = config('mail.from.address');

        $this->line("Mailer: <info>{$mailer}</info>");
        $this->line("Remetente: <info>{$from}</info>");

        if ($mailer === 'resend' && blank(config('services.resend.key'))) {
            $this->error('RESEND_KEY não está definida no .env.');

            return self::FAILURE;
        }

        try {
            Mail::raw(
                "Teste de envio da Time Plus.\n\nMailer: {$mailer}\nEnviado em: " . now()->format('d/m/Y H:i:s'),
                fn ($message) => $message->to($to)->subject('Teste de e-mail - Time Plus')
            );
        } catch (\Exception $e) {
            $this->error("Falha no envio: {$e->getMessage()}");

            return self::FAILURE;
        }

        $this->info("E-mail de teste enviado para {$to}.");

        return self::SUCCESS;
    }
}

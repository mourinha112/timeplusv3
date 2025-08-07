<?php

namespace App\Providers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;
use SendGrid;
use SendGrid\Mail\Mail as SendGridMail;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Mime\MessageConverter;

class SendGridServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot()
    {
        Mail::extend('sendgrid', function (array $config = []) {
            return new SendGridTransport();
        });
    }
}

class SendGridTransport implements TransportInterface
{
    protected $sendgrid;

    public function __construct()
    {
        $this->sendgrid = new SendGrid(config('services.sendgrid.key'));
    }

    public function send($message, $envelope = null): ?SentMessage
    {
        $email = new SendGridMail();

        $from = $message->getFrom()[0];
        $email->setFrom($from->getAddress(), $from->getName());

        foreach ($message->getTo() as $to) {
            $email->addTo($to->getAddress(), $to->getName());
        }

        $email->setSubject($message->getSubject());

        if ($message->getHtmlBody()) {
            $email->addContent("text/html", $message->getHtmlBody());
        }

        if ($message->getTextBody()) {
            $email->addContent("text/plain", $message->getTextBody());
        }

        try {
            $response = $this->sendgrid->send($email);

            return new SentMessage(MessageConverter::toEmail($message), $envelope ?? $message);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function __toString(): string
    {
        return 'sendgrid';
    }
}

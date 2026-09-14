<?php

declare(strict_types=1);

namespace App\Auth\Service;

use App\Auth\Entity\User\Email;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email as SymfonyEmail;
use Twig\Environment;

final class CredentialsSender
{
    public const string TEMPLATE = 'auth/join/credentials.html.twig';

    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly Environment $twig
    ) {}

    public function send(Email $email, string $password): void
    {
        $message = new SymfonyEmail()
            ->subject('Данные для входа на сайт.')
            ->to($email->getValue())
            ->html($this->twig->render(self::TEMPLATE, ['password' => $password, 'email' => $email->getValue()]));
        try {
            $this->mailer->send($message);
        } catch (TransportExceptionInterface $e) {
            throw new TransportException($e->getMessage());
        }
    }
}

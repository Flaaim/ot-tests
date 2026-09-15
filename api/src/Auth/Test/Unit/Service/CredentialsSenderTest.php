<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Service;

use App\Auth\Entity\User\Email;
use App\Auth\Service\CredentialsSender;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email as SymfonyEmail;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

/**
 * @internal
 * @coversNothing
 */
final class CredentialsSenderTest extends TestCase
{
    public function testSuccess(): void
    {
        $to = new Email('user@app.test');
        $password = 'password';

        $template = 'auth/join/credentials.html.twig';

        $loader = new ArrayLoader([
            $template => '<p>Credentials: {email} {password}</p>',
        ]);
        $twig = new Environment($loader);

        $symfonyEmail = new SymfonyEmail()
            ->to($to->getValue())
            ->subject('Данные для входа на сайт.')
            ->html($twig->render($template, ['email' => $to->getValue(), 'password' => $password]));

        $mailer = $this->createMock(MailerInterface::class);
        $mailer->expects(self::once())->method('send')
            ->willReturnCallback(static function (SymfonyEmail $message) use ($symfonyEmail): int {
                self::assertEquals($symfonyEmail->getTo(), $message->getTo());
                self::assertEquals($symfonyEmail->getSubject(), $message->getSubject());
                self::assertStringContainsString((string)$symfonyEmail->getHtmlBody(), (string)$message->getHtmlBody());
                return 1;
            });

        $sender = new CredentialsSender($mailer, $twig);

        $sender->send($to, $password);
    }

    public function testError(): void
    {
        $to = new Email('user@app.test');
        $password = 'password';

        $template = 'auth/join/credentials.html.twig';

        $loader = new ArrayLoader([
            $template => '<p>Credentials: {email} {password}</p>',
        ]);
        $twig = new Environment($loader);

        $mailer = $this->createMock(MailerInterface::class);
        $mailer->expects(self::once())->method('send')->willThrowException(new TransportException('Transport failed'));

        $sender = new CredentialsSender($mailer, $twig);
        self::expectException(TransportException::class);

        $sender->send($to, $password);
    }
}

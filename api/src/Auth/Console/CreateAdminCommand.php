<?php

declare(strict_types=1);

namespace App\Auth\Console;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\User;
use App\Auth\Entity\User\UserRepository;
use App\Auth\Service\PasswordHasher;
use App\Infrastructure\Doctrine\Flusher;
use DateTimeImmutable;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/** @psalm-suppress UnusedClass */
#[AsCommand(
    name: 'app:profile:create-admin',
    description: 'Создает первого пользователя с ролью администратора.'
)]
final class CreateAdminCommand extends Command
{
    public function __construct(
        private readonly UserRepository $users,
        private readonly Flusher $flusher,
        private readonly PasswordHasher $passwordHasher,
    ) {
        parent::__construct();
    }

    public function __invoke(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $emailValue = (string)$input->getArgument('email');
        $password = (string)$input->getArgument('password');

        $email = new Email($emailValue);

        if ($this->users->hasByEmail($email)) {
            $io->error('Профиль с таким email уже существует.');
            return Command::FAILURE;
        }

        $user = User::createAdmin(
            Id::generate(),
            new DateTimeImmutable(),
            $email,
            $this->passwordHasher->hash($password)
        );

        $this->users->add($user);

        $this->flusher->flush();

        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'Email администратора')
            ->addArgument('password', InputArgument::REQUIRED, 'Пароль администратора');
    }
}

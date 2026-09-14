<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Profile\Add;

use App\Infrastructure\Http\Validator\Validator;
use App\Profile\Command\Add\Command;
use App\Profile\Command\Add\Handler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class RequestAction
{
    public function __construct(
        private readonly Handler $handler,
        private readonly Validator $validator
    ) {}

    #[Route('/v1/admin/profiles', name: 'admin.profiles.add', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function __invoke(Request $request): Response
    {
        $body = $request->toArray();
        $email = $body['email'] ?? '';
        $password = $body['password'] ?? '';
        $name = $body['name'] ?? '';
        $surname = $body['surname'] ?? '';

        $command = new Command($email, $password, $name, $surname);

        $this->validator->validate($command);

        $this->handler->handle($command);

        return new Response(null, Response::HTTP_CREATED);
    }
}

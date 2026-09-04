<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Testing\Attempt\Finish;

use App\Infrastructure\Http\Validator\Validator;
use App\Testing\Command\Attempt\Finish\Command;
use App\Testing\Command\Attempt\Finish\Handler;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RequestAction
{
    public function __construct(
        private readonly Handler $handler,
        private readonly Validator $validator,
        private readonly Security $security,
    ) {}

    #[Route('/v1/testing/attempts/{id}/finish', name: 'testing.attempt.finish', methods: ['PUT'])]
    public function __invoke(string $id): Response
    {
        $user = $this->security->getUser();
        if (null === $user) {
            return new JsonResponse(null, Response::HTTP_UNAUTHORIZED);
        }
        $command = new Command($id);

        $this->validator->validate($command);

        $this->handler->handle($command);

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Testing\Attempt\Launch;

use App\Infrastructure\Http\Validator\Validator;
use App\Testing\Command\Attempt\Launch\Command;
use App\Testing\Command\Attempt\Launch\Handler;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class RequestAction
{
    public function __construct(
        private readonly Handler $handler,
        private readonly Validator $validator,
        private readonly Security $security,
    ) {}

    #[Route('/v1/testing/attempts', name: 'testing.attempt.launch', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function __invoke(Request $request): Response
    {
        $user = $this->security->getUser();
        if (null === $user) {
            return new JsonResponse(null, Response::HTTP_UNAUTHORIZED);
        }

        $userId = $user->getUserIdentifier();
        $body = $request->toArray();

        $attemptId = Uuid::uuid4()->toString();
        $testId = $body['testId'] ?? '';
        $ticketNumber = (int)($body['ticketNumber'] ?? 0);

        $command = new Command($attemptId, $testId, $userId, $ticketNumber);

        $this->validator->validate($command);

        $this->handler->handle($command);

        return new JsonResponse(null, Response::HTTP_CREATED);
    }
}

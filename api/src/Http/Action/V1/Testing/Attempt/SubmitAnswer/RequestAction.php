<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Testing\Attempt\SubmitAnswer;

use App\Infrastructure\Http\Validator\Validator;
use App\Testing\Command\Attempt\SubmitAnswer\Command;
use App\Testing\Command\Attempt\SubmitAnswer\Handler;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RequestAction
{
    public function __construct(
        private readonly Handler $handler,
        private readonly Validator $validator,
        private readonly Security $security,
    ) {}

    #[Route('/v1/testing/attempts/{id}/submit', name: 'testing.attempt.submit', methods: ['POST'])]
    public function __invoke(Request $request, string $id): Response
    {
        $user = $this->security->getUser();
        if (null === $user) {
            return new JsonResponse(null, Response::HTTP_UNAUTHORIZED);
        }

        $body = $request->toArray();
        $questionId = $body['questionId'] ?? '';
        $selectedAnswersIds = $body['selectedAnswersIds'] ?? [];

        $command = new Command($id, $questionId, $selectedAnswersIds);

        $this->validator->validate($command);

        $this->handler->handle($command);

        return new JsonResponse(null, Response::HTTP_CREATED);
    }
}

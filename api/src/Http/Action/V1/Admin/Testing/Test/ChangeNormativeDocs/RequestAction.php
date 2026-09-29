<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Admin\Testing\Test\ChangeNormativeDocs;

use App\Infrastructure\Http\Validator\Validator;
use App\Testing\Command\Test\ChangeNormativeDocs\Command;
use App\Testing\Command\Test\ChangeNormativeDocs\Handler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final readonly class RequestAction
{
    public function __construct(
        private Handler $handler,
        private Validator $validator
    ) {}

    #[Route('/v1/admin/testing/tests/{id}/change-npa', name: 'admin.testing.test.change-npa', methods: ['PUT'])]
    #[IsGranted('ROLE_ADMIN')]
    public function __invoke(string $id, Request $request): Response
    {
        $body = $request->toArray();

        $normativeDocs = (array)($body['normativeDocs'] ?? []);

        $command = new Command($id, $normativeDocs);

        $this->validator->validate($command);

        $this->handler->handle($command);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}

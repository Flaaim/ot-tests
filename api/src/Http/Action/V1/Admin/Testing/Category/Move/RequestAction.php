<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Admin\Testing\Category\Move;

use App\Infrastructure\Http\Validator\Validator;
use App\Testing\Command\Category\Move\Command;
use App\Testing\Command\Category\Move\Handler;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    #[Route('/v1/admin/testing/categories/{id}/move', name: 'admin.testing.categories.move', methods: ['PUT'])]
    #[IsGranted('ROLE_ADMIN')]
    public function __invoke(Request $request, string $id): Response
    {
        $body = $request->toArray();
        $parentId = $body['parentId'] ?? null;

        $command = new Command($id, $parentId);

        $this->validator->validate($command);

        $this->handler->handle($command);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}

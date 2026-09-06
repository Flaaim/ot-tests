<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Admin\Testing\Category\Rename;

use App\Infrastructure\Http\Validator\Validator;
use App\Testing\Command\Category\Rename\Command;
use App\Testing\Command\Category\Rename\Handler;
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

    #[Route('/v1/admin/testing/categories/{id}/rename', name: 'admin.testing.categories.rename', methods: ['PUT'])]
    #[IsGranted('ROLE_ADMIN')]
    public function __invoke(Request $request, string $id): Response
    {
        $body = $request->toArray();
        $name = $body['name'] ?? '';

        $command = new Command($id, $name);

        $this->validator->validate($command);

        $this->handler->handle($command);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}

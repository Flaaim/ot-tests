<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Profile\ChangePersonalData;

use App\Infrastructure\Http\Validator\Validator;
use App\OAuth\Entity\UserAdapter;
use App\Profile\Command\ChangePersonalData\Command;
use App\Profile\Command\ChangePersonalData\Handler;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final readonly class RequestAction
{
    public function __construct(
        private Handler $handler,
        private Validator $validator,
        private Security $security,
    ) {}

    #[Route('/v1/me/change-personal-data', name: 'me.change.personal.data', methods: ['PUT'])]
    public function __invoke(Request $request): Response
    {
        /** @var UserAdapter|null $currentUser */
        $currentUser = $this->security->getUser();
        if (null === $currentUser) {
            return new JsonResponse(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }
        $userId = $currentUser->getUserIdentifier();
        $body = $request->toArray();

        $name = (string)($body['name'] ?? '');
        $surname = (string)($body['surname'] ?? '');

        $command = new Command($userId, $name, $surname);

        $this->validator->validate($command);

        $this->handler->handle($command);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}

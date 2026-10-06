<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Notification\Message\GetUnreadCount;

use App\Infrastructure\Http\Validator\Validator;
use App\Notification\Query\Message\GetUnreadCount\Query;
use App\Notification\Query\Message\GetUnreadCount\QueryHandler;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final readonly class RequestAction
{
    public function __construct(
        private QueryHandler $handler,
        private Validator $validator,
        private Security $security,
    ) {}

    #[Route('/v1/messages/unread/count', name: 'notifications.message.unread.count', methods: ['GET'])]
    public function __invoke(): Response
    {
        $user = $this->security->getUser();
        if (null === $user) {
            return new JsonResponse(null, Response::HTTP_UNAUTHORIZED);
        }
        $profileId = $user->getUserIdentifier();

        $query = new Query($profileId);

        $this->validator->validate($query);

        $result = $this->handler->handle($query);

        return new JsonResponse(['count' => $result], Response::HTTP_OK);
    }
}

<?php

declare(strict_types=1);

namespace App\Notification\Query\Notification\GetPaginated;

final readonly class NotificationDTO
{
    public function __construct(
        public string $notificationId,
        public string $subject,
        public string $status,
        public string $createdAt,
        public int $countMessages,
        public int $readCountMessages,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            notificationId: $data['notification_id'],
            subject: $data['subject'],
            status: $data['status'],
            createdAt: $data['created_at'],
            countMessages: $data['count_messages'],
            readCountMessages: $data['read_count_messages'],
        );
    }
}

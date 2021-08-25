<?php
declare(strict_types=1);

namespace App\User\Domain;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use DateTimeImmutable;

class Session
{
    private UuidInterface $id;

    private string $token;

    private DateTimeImmutable $createdAt;
    private DateTimeImmutable $updatedAt;
    private DateTimeImmutable $deletedAt;

    private DateTimeImmutable $tokenValidTo;

    public function __construct(
        private User $user,
        private ?string $firstLoginIp
    ) {
        $this->id = Uuid::uuid4();
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
        $this->deletedAt = new DateTimeImmutable();

        $this->token = self::createToken();

        $this->tokenValidTo = new DateTimeImmutable('+12 hours');
    }

    private static function createToken(): string
    {
        return bin2hex(random_bytes(25));
    }
}
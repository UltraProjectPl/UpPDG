<?php

declare(strict_types=1);

namespace App\User\Domain;

use App\SharedKernel\Domain\TimeStamp;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use DateTimeImmutable;

class Session
{
    use TimeStamp;

    private UuidInterface $id;

    private string $token;

    private DateTimeImmutable $tokenValidTo;

    public function __construct(
        private User $user,
        private ?string $firstLoginIp
    ) {
        $this->id = Uuid::uuid4();

        $this->token = self::createToken();

        $this->tokenValidTo = new DateTimeImmutable('+12 hours');

        $this->setTimeStamp();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getTokenValidTo(): DateTimeImmutable
    {
        return $this->tokenValidTo;
    }

    public function getFirstLoginIp(): ?string
    {
        return $this->firstLoginIp;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getDeletedAt(): DateTimeImmutable
    {
        return $this->deletedAt;
    }

    private static function createToken(): string
    {
        return bin2hex(random_bytes(25));
    }
}

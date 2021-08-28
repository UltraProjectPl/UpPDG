<?php
declare(strict_types=1);

namespace App\User\Domain;

use App\SharedKernel\Domain\TimeStamp;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use DateTimeImmutable;

class User
{
    use TimeStamp;

    private UuidInterface $id;

    public function __construct(
        private string $email,
        private string $firstName,
        private string $lastName,
        private string $password,
    ) {
        $this->id = Uuid::uuid4();
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
        $this->deletedAt = new DateTimeImmutable();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getPassword(): string
    {
        return $this->password;
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
}
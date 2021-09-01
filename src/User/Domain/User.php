<?php
declare(strict_types=1);

namespace App\User\Domain;

use App\SharedKernel\Domain\Security\PasswordHashing;
use App\SharedKernel\Domain\Security\PlainPassword;
use App\SharedKernel\Domain\TimeStamp;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use JsonSerializable;

class User implements JsonSerializable
{
    use TimeStamp;

    private UuidInterface $id;
    private string $password;
    private string $slug;

    public function __construct(
        private string $email,
        private string $firstName,
        private string $lastName,
        Users $repository,
        PlainPassword $password,
    ) {
        $this->id = Uuid::uuid4();

        $this->setTimeStamp();

        $this->changePassword($password);

        $this->slug = $repository->getUniqueSlug($this);
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

    public function getSlug(): string
    {
        return $this->slug;
    }

    /** @return array<string, mixed> */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'password' => $this->password,
            'slug' => $this->slug,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
            'deletedAt' => $this->deletedAt,
        ];
    }

    private function changePassword(PlainPassword $plainPassword): self
    {
        $this->password = PasswordHashing::passwordHash($plainPassword);

        return $this;
    }
}
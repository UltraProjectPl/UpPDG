<?php

declare(strict_types=1);

namespace App\User\Application\Command;

use App\SharedKernel\Application\Command\CommandInterface;
use App\SharedKernel\Domain\Security\PlainPassword;

final class CreateUser implements CommandInterface
{
    public function __construct(
        private string $email,
        private string $firstName,
        private string $lastName,
        private PlainPassword $password,
    ) {
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

    public function getPassword(): PlainPassword
    {
        return $this->password;
    }
}

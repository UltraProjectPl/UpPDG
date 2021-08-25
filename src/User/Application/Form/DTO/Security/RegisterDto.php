<?php
declare(strict_types=1);

namespace App\User\Application\Form\DTO\Security;

use App\User\Application\Command\CreateUser;

final class RegisterDto
{
    public ?string $email = null;
    public ?string $firstName = null;
    public ?string $lastName = null;
    public ?string $password = null;

    public function toCommand(): CreateUser
    {
        return new CreateUser(
            email: $this->email,
            firstName: $this->firstName,
            lastName: $this->lastName,
            password: $this->password,
        );
    }
}
<?php

declare(strict_types=1);

namespace App\User\Application\Form\Dto\Security;

use App\SharedKernel\Domain\Security\PlainPassword;
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
            password: new PlainPassword($this->password),
        );
    }
}

<?php
declare(strict_types=1);

namespace App\User\Infrastructure\SymfonyIntegration\Security;

use App\User\Domain\User as DomainUser;
use Symfony\Component\Security\Core\User\UserInterface;

final class User implements UserInterface
{
    public const ROLE = 'ROLE_USER';

    public function __construct(private DomainUser $user)
    {
    }

    public function getUser(): DomainUser
    {
        return $this->user;
    }

    public function getUsername(): string
    {
        return $this->user->getEmail();
    }


    public function getUserIdentifier(): string
    {
        return $this->user->getEmail();
    }

    public function getPassword(): string
    {
        return $this->user->getPassword();
    }

    public function getRoles(): array
    {
        return [self::ROLE];
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
    }
}
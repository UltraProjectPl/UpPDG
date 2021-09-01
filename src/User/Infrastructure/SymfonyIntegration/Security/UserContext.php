<?php

declare(strict_types=1);

namespace App\User\Infrastructure\SymfonyIntegration\Security;

use App\User\Application\Security\UserContextInterface;
use App\User\Domain\User;
use App\User\Infrastructure\SymfonyIntegration\Security\User as SecurityUser;
use Symfony\Component\Security\Core\Security;

final class UserContext implements UserContextInterface
{
    public function __construct(private Security $security)
    {
    }

    public function isLoggedIn(): bool
    {
        return null !== $this->getCurrentUser();
    }

    public function getCurrentUser(): ?User
    {
        /** @var SecurityUser $securityUser */
        $securityUser = $this->security->getUser();

        return $securityUser->getUser();
    }
}

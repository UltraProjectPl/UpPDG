<?php
declare(strict_types=1);

namespace App\User\Infrastructure\SymfonyIntegration\Security;

use App\SharedKernel\Application\Bus\QueryBusInterface;
use App\User\Application\Query\SessionByToken;
use App\User\Infrastructure\SymfonyIntegration\Security\User as SecurityUser;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use RuntimeException;

final class UserProvider implements UserProviderInterface
{

    public function __construct(private QueryBusInterface $queryBus)
    {
    }


    public function loadUserByUsername(string $token): UserInterface
    {
        if ('' === $token) {
            throw new UserNotFoundException('No username provider.');
        }

        $sessions = $this->queryBus->query(new SessionByToken($token));

        if (0 === count($sessions)) {
            throw new RuntimeException('Failed to authorization.');
        }

        return new SecurityUser($sessions[0]->getUser());
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        return $this->loadUserByUsername($user->getUserIdentifier());
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        return $this->loadUserByUsername($identifier);
    }

    public function supportsClass(string $class): bool
    {
        return SecurityUser::class === $class;
    }
}
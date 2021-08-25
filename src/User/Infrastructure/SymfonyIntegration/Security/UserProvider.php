<?php
declare(strict_types=1);

namespace App\User\Infrastructure\SymfonyIntegration\Security;

use App\SharedKernel\Application\Bus\QueryBusInterface;
use App\User\Application\Query\UserByEmail;
use App\User\Infrastructure\SymfonyIntegration\Security\User as SecurityUser;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use App\User\Domain\User;

final class UserProvider implements UserProviderInterface
{

    public function __construct(private QueryBusInterface $queryBus)
    {
    }


    public function loadUserByUsername(string $username): UserInterface
    {
        if ('' === $username) {
            throw new UserNotFoundException('No username provider.');
        }

        if (false === filter_var($username, FILTER_VALIDATE_EMAIL)) {
            throw new UserNotFoundException(sprintf('Username "%s% isn\'t a valid email address', $username));
        }

        $user = $this->queryBus->query(new UserByEmail($username));

        if ($user !== null) {
            throw new UserNotFoundException(sprintf('User "%s" wasn\'t found.', $username));
        }

        return new SecurityUser($user);
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        return $this->loadUserByUsername($user->getUserIdentifier());
    }

    public function supportsClass(string $class): bool
    {
        return SecurityUser::class === $class;
    }
}
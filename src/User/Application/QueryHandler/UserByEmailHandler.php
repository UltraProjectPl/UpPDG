<?php

declare(strict_types=1);

namespace App\User\Application\QueryHandler;

use App\SharedKernel\Application\Bus\QueryHandlerInterface;
use App\User\Application\Query\UserByEmail;
use App\User\Domain\User;
use App\User\Domain\Users;

final class UserByEmailHandler implements QueryHandlerInterface
{
    public function __construct(private Users $users)
    {
    }

    public function __invoke(UserByEmail $query): ?User
    {
        return $this->users->findOneByEmail($query->getEmail());
    }
}

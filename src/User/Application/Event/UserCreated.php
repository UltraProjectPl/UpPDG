<?php
declare(strict_types=1);

namespace App\User\Application\Event;

use App\SharedKernel\Application\Event\EventInterface;
use App\User\Domain\User;

final class UserCreated implements EventInterface
{
    public function __construct(private User $user)
    {
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
<?php
declare(strict_types=1);

namespace App\User\Application\Event;

use App\SharedKernel\Application\Event\EventInterface;
use App\User\Domain\Session;
use App\User\Domain\User;

final class UserLogged implements EventInterface
{
    public function __construct(private User $user, private Session $session)
    {
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getSession(): Session
    {
        return $this->session;
    }
}
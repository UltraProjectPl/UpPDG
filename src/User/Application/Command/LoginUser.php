<?php
declare(strict_types=1);

namespace App\User\Application\Command;

use App\SharedKernel\Application\Command\CommandInterface;
use App\User\Domain\User;

final class LoginUser implements CommandInterface
{
    public function __construct(private User $user, private ?string $ip = null)
    {
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }
}
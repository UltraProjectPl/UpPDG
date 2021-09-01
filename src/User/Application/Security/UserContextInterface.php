<?php

declare(strict_types=1);

namespace App\User\Application\Security;

use App\User\Domain\User;

interface UserContextInterface
{
    public function isLoggedIn(): bool;

    public function getCurrentUser(): ?User;
}

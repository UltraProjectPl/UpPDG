<?php
declare(strict_types=1);

namespace App\User\Application\Query;

use App\SharedKernel\Application\Query\QueryInterface;

final class UserByEmail implements QueryInterface
{
    public function __construct(private string $email)
    {
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
<?php
declare(strict_types=1);

namespace App\User\Application\Query;

use App\SharedKernel\Application\Query\QueryInterface;

final class SessionByToken implements QueryInterface
{
    public function __construct(private string $token)
    {
    }

    public function getToken(): string
    {
        return $this->token;
    }
}
<?php

declare(strict_types=1);

namespace App\SharedKernel\Application\Lock;

final class LockParameters
{
    public function __construct(private string $resource, private int $ttl)
    {
    }

    public function getResource(): string
    {
        return $this->resource;
    }

    public function getTtl(): int
    {
        return $this->ttl;
    }
}

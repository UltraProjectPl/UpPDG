<?php

declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\SymfonyIntegration\Lock;

use App\SharedKernel\Application\Lock\LockInterface;
use Symfony\Component\Lock\LockInterface as SymfonyLockInterface;

final class Lock implements LockInterface
{
    public function __construct(private SymfonyLockInterface $symfonyLock)
    {
        $this->symfonyLock->acquire(true);
    }

    public function release(): void
    {
        $this->symfonyLock->release();
    }
}

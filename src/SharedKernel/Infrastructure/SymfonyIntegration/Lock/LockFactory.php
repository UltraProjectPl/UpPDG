<?php

declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\SymfonyIntegration\Lock;

use App\SharedKernel\Application\Lock\LockFactoryInterface;
use App\SharedKernel\Application\Lock\LockInterface;
use App\SharedKernel\Application\Lock\LockParameters;
use Symfony\Component\Lock\LockFactory as SymfonyLockFactory;

final class LockFactory implements LockFactoryInterface
{
    public function __construct(private SymfonyLockFactory $symfonyFactory)
    {
    }

    public function acquire(LockParameters $lockParameters): LockInterface
    {
        return new Lock($this->symfonyFactory->createLock($lockParameters->getResource(), $lockParameters->getTtl()));
    }
}

<?php

declare(strict_types=1);

namespace App\SharedKernel\Application\Lock;

interface LockFactoryInterface
{
    public function acquire(LockParameters $lockParameters): LockInterface;
}

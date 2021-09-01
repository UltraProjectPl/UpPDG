<?php

declare(strict_types=1);

namespace App\SharedKernel\Application\Bus;

use App\SharedKernel\Application\Lock\LockParameters;

interface CommandLockingResource
{
    public function getLockParameters(): LockParameters;
}

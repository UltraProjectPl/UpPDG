<?php

declare(strict_types=1);

namespace App\SharedKernel\Application\Bus;

use App\SharedKernel\Application\Command\CommandInterface;

interface CommandBusInterface
{
    public function dispatch(CommandInterface $command): void;
}

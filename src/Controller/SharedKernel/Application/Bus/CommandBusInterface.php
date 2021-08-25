<?php
declare(strict_types=1);

namespace App\Controller\SharedKernel\Application\Bus;

use App\Controller\SharedKernel\Application\Command\CommandInterface;

interface CommandBusInterface
{
    public function dispatch(CommandInterface $command): void;
}
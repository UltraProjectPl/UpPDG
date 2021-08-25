<?php
declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\SymfonyIntegration\Messenger;

use App\SharedKernel\Application\Bus\CommandBusInterface;
use App\SharedKernel\Application\Command\CommandInterface;

class CommandBus extends Bus implements CommandBusInterface
{
    public function dispatch(CommandInterface $command): void
    {
        $this->bus->dispatch($this->addTimestamp($command));
    }
}
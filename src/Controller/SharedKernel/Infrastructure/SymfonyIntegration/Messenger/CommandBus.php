<?php
declare(strict_types=1);

namespace App\Controller\SharedKernel\Infrastructure\SymfonyIntegration\Messenger;

use App\Controller\SharedKernel\Application\Bus\CommandBusInterface;
use App\Controller\SharedKernel\Application\Command\CommandInterface;

class CommandBus extends Bus implements CommandBusInterface
{
    public function dispatch(CommandInterface $command): void
    {
        $this->bus->dispatch($this->addTimestamp($command));
    }
}
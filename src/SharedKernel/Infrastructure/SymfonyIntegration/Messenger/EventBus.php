<?php
declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\SymfonyIntegration\Messenger;

use App\SharedKernel\Application\Bus\EventBusInterface;
use App\SharedKernel\Application\Event\EventInterface;

final class EventBus extends Bus implements EventBusInterface
{
    public function dispatch(EventInterface $event): void
    {
        $this->bus->dispatch($this->addTimestamp($event));
    }
}
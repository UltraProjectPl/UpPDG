<?php
declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\SymfonyIntegration\Messenger;

use App\SharedKernel\Application\Bus\EvenBusInterface;
use App\SharedKernel\Application\Event\EventInterface;

class EventBus extends Bus implements EvenBusInterface
{
    public function dispatch(EventInterface $event): void
    {
        $this->bus->dispatch($this->addTimestamp($event));
    }
}
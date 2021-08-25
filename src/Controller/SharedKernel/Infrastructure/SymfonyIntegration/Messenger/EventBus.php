<?php
declare(strict_types=1);

namespace App\Controller\SharedKernel\Infrastructure\SymfonyIntegration\Messenger;

use App\Controller\SharedKernel\Application\Bus\EvenBusInterface;
use App\Controller\SharedKernel\Application\Event\EventInterface;

class EventBus extends Bus implements EvenBusInterface
{
    public function dispatch(EventInterface $event): void
    {
        $this->bus->dispatch($this->addTimestamp($event));
    }
}
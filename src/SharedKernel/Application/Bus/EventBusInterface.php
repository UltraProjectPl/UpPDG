<?php

declare(strict_types=1);

namespace App\SharedKernel\Application\Bus;

use App\SharedKernel\Application\Event\EventInterface;

interface EventBusInterface
{
    public function dispatch(EventInterface $event): void;
}

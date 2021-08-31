<?php
declare(strict_types=1);

namespace App\SharedKernel\Application\Event;

use App\SharedKernel\Application\Bus\CommandBusInterface;
use App\SharedKernel\Application\Bus\EventSubscriberInterface;

final class DayHasPassed implements EventSubscriberInterface
{
    public function __construct(private CommandBusInterface $commandBus)
    {
    }

    public function __invoke(DayHasPassed $event): void
    {

    }
}
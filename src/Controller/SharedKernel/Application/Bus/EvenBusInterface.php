<?php
declare(strict_types=1);

namespace App\Controller\SharedKernel\Application\Bus;

use App\Controller\SharedKernel\Application\Event\EventInterface;

interface EvenBusInterface
{
    public function dispatch(EventInterface $event): void;
}
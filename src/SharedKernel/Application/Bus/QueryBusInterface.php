<?php

declare(strict_types=1);

namespace App\SharedKernel\Application\Bus;

interface QueryBusInterface
{
    public function query(object $query): mixed;
}

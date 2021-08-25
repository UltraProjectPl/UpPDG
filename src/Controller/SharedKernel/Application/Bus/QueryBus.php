<?php
declare(strict_types=1);

namespace App\Controller\SharedKernel\Application\Bus;

interface QueryBus
{
    public function query(object $query): mixed;
}
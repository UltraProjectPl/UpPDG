<?php
declare(strict_types=1);

namespace App\SharedKernel\Application\Bus;

use App\SharedKernel\Application\Notification\NotificationInterface;

interface NotificationBusInterface
{
    public function send(NotificationInterface $notification): void;
}
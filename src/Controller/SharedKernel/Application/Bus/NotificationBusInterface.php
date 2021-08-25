<?php
declare(strict_types=1);

namespace App\Controller\SharedKernel\Application\Bus;

use App\Controller\SharedKernel\Application\Notification\NotificationInterface;

interface NotificationBusInterface
{
    public function send(NotificationInterface $notification): void;
}
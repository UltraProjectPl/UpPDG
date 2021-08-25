<?php
declare(strict_types=1);

namespace App\Controller\SharedKernel\Application\Bus;

use App\Controller\SharedKernel\Application\Notification\NotificationInterface;

interface NotificationBus
{
    public function send(NotificationInterface $notification): void;
}
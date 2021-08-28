<?php
declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\SymfonyIntegration\Messenger;

use App\SharedKernel\Application\Bus\NotificationBusInterface;
use App\SharedKernel\Application\Notification\NotificationInterface;

final class NotificationBus extends Bus implements NotificationBusInterface
{
    private array $notifications = [];

    public function send(NotificationInterface $notification): void
    {
        $this->notifications[] = $notification;
    }

    public function release(): void
    {
        foreach ($this->notifications as $notification) {
            $this->bus->dispatch($notification);
        }

        $this->reset();
    }

    private function reset()
    {
        $this->notifications = [];
    }
}
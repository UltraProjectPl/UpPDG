<?php

declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\SymfonyIntegration\Messenger\Middleware;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;

final class ResetEntityManagerMiddleware implements MiddlewareInterface
{
    public function __construct(private ManagerRegistry $managerRegistry)
    {
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        if (null !== $envelope->last(ReceivedStamp::class)) {
            $this->managerRegistry->resetManager();
        }

        return $stack->next()->handle($envelope, $stack);
    }
}

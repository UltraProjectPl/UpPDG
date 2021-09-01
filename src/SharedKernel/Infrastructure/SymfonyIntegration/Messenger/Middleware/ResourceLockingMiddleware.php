<?php

namespace App\SharedKernel\Infrastructure\SymfonyIntegration\Messenger\Middleware;

use App\SharedKernel\Application\Bus\AsynchronousCommandInterface;
use App\SharedKernel\Application\Bus\CommandLockingResource;
use App\SharedKernel\Application\Lock\LockInterface;
use App\SharedKernel\Application\Lock\LockFactoryInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;

final class ResourceLockingMiddleware implements MiddlewareInterface
{
    public function __construct(private LockFactoryInterface $lockFactory)
    {
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        if (
            true === $envelope->getMessage() instanceof AsynchronousCommandInterface &&
            null === $envelope->last(ReceivedStamp::class)
        ) {
            return $stack->next()->handle($envelope, $stack);
        }

        $message = $envelope->getMessage();
        $lock = null;

        if (true === $message instanceof CommandLockingResource) {
            $lock = $this->lockFactory->acquire($message->getLockParameters());
        }

        $result = $stack->next()->handle($envelope, $stack);

        if (true === $lock instanceof LockInterface) {
            $lock->release();
        }

        return $result;
    }
}

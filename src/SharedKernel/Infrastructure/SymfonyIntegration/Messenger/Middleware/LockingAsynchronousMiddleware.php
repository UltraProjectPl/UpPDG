<?php

declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\SymfonyIntegration\Messenger\Middleware;

use App\SharedKernel\Application\Bus\AsynchronousCommandInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;
use Throwable;

final class LockingAsynchronousMiddleware implements MiddlewareInterface
{
    private bool $isExecuting = false;

    /** @var DeferredEnvelope[] */
    private array $queue = [];

    public function __construct(private LockingMiddleware $lockingMiddleware, private LoggerInterface $logger)
    {
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        if (
            true === $this->isExecuting &&
            true === $envelope->getMessage() instanceof AsynchronousCommandInterface &&
            null === $envelope->last(ReceivedStamp::class)
        ) {
            $this->queue[] = new DeferredEnvelope($envelope, $stack);

            return $envelope;
        }

        $this->isExecuting = true;

        try {
            $returnValue = $stack->next()->handle($envelope, $stack);

            if (false === $this->lockingMiddleware->isExecuting()) {
                $this->isExecuting = false;
                $this->executeQueuedJobs();
            }
        } catch (Throwable $e) {
            $this->isExecuting = false;
            $this->queue = [];

            throw $e;
        }

        return $returnValue;
    }

    private function executeQueuedJobs(): void
    {
        while ($resumeCommand = array_shift($this->queue)) {
            $commandsClasses = array_map(
                fn (DeferredEnvelope $envelope): string => get_class($envelope->getEnvelope()->getMessage()),
                $this->queue
            );

            $this->logger->info(sprintf(
                'About to send asynchronous command "%s" and the asynchronous queue holds: "%s"',
                get_class($resumeCommand->getEnvelope()->getMessage()),
                implode('", "', $commandsClasses)
            ));

            $resumeCommand();
        }
    }
}

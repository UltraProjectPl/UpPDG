<?php

declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\SymfonyIntegration\Messenger\Middleware;

use Assert\Assertion;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Throwable;

final class LockingMiddleware implements MiddlewareInterface
{
    private bool $isExecuting = false;

    /** @var DeferredEnvelope[] */
    private array $queue = [];

    public function __construct(private LoggerInterface $logger)
    {
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $this->queue[] = new DeferredEnvelope($envelope, $stack);

        if ($this->isExecuting) {
            return $envelope;
        }

        $this->isExecuting = true;

        try {
            $returnValue = $this->executeQueuedJobs();
        } catch (Throwable $e) {
            $this->isExecuting = false;
            $this->queue = [];

            throw $e;
        }
        $this->isExecuting = false;

        return $returnValue;
    }

    public function isExecuting(): bool
    {
        return $this->isExecuting;
    }

    private function executeQueuedJobs(): Envelope
    {
        $returnValues = [];
        while ($resumeCommand = array_shift($this->queue)) {
            $commandsClasses = array_map(
                fn (DeferredEnvelope $envelope): string => get_class($envelope->getEnvelope()->getMessage()),
                $this->queue
            );

            $this->logger->info(sprintf(
                'About to execute command "%s" and the queue holds: "%s"',
                get_class($resumeCommand->getEnvelope()->getMessage()),
                implode('", "', $commandsClasses)
            ));

            $returnValues[] = $resumeCommand();
        }

        $returnValue = array_shift($returnValues);
        Assertion::notNull($returnValue);

        return $returnValue;
    }
}

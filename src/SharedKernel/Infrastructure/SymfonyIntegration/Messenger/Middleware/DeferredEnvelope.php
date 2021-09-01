<?php

namespace App\SharedKernel\Infrastructure\SymfonyIntegration\Messenger\Middleware;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\StackInterface;

final class DeferredEnvelope
{
    public function __construct(private Envelope $envelope, private StackInterface $stack)
    {
    }

    public function getEnvelope(): Envelope
    {
        return $this->envelope;
    }

    public function __invoke(): Envelope
    {
        return $this->stack->next()->handle($this->envelope, $this->stack);
    }
}

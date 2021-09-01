<?php

declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\SymfonyIntegration\Messenger\Middleware;

use Symfony\Component\Messenger\Envelope;
use Throwable;

final class AsynchronousCommandResult
{
    public function __construct(private Envelope $command, private bool $success, private ?Throwable $throwable)
    {
    }

    public function getCommand(): Envelope
    {
        return $this->command;
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function getThrowable(): ?Throwable
    {
        return $this->throwable;
    }
}

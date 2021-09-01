<?php

declare(strict_types=1);

namespace App\SharedKernel\Application\Validation;

use Stringable;

final class ValidationError
{
    public function __construct(private Stringable|string $path, private Stringable|string $message)
    {
    }

    public function getPath(): Stringable|string
    {
        return $this->path;
    }

    public function getMessage(): Stringable|string
    {
        return $this->message;
    }
}

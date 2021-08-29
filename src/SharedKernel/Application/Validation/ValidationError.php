<?php
declare(strict_types=1);

namespace App\SharedKernel\Application\Validation;

final class ValidationError
{
    public function __construct(private string $path, private string $message)
    {
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
<?php
declare(strict_types=1);

namespace App\SharedKernel\Domain\Security;

use Assert\Assertion;

final class PlainPassword
{
    public const MIN_LENGTH = 6;
    public const MAX_LENGTH = 32;

    public function __construct(private string $value)
    {
        Assertion::minLength($this->value, self::MIN_LENGTH, sprintf('Provided password value is too short (%s)', mb_strlen($this->value)));
        Assertion::maxLength($this->value, self::MAX_LENGTH, sprintf('Provided password value is too long (%s)', mb_strlen($this->value)));
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
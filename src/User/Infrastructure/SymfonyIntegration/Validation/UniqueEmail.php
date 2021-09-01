<?php

declare(strict_types=1);

namespace App\User\Infrastructure\SymfonyIntegration\Validation;

use Symfony\Component\Validator\Constraint;

class UniqueEmail extends Constraint
{
    public string $message = 'Email must be unique';
}

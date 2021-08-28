<?php
declare(strict_types=1);

namespace App\Employer\Infrastructure\SymfonyIntegration\Validation;

use Symfony\Component\Validator\Constraint;

class UniqueTitlePerUser extends Constraint
{
    public string $message = 'Title must be unique';
}
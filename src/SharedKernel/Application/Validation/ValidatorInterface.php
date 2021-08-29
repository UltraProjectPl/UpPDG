<?php
declare(strict_types=1);

namespace App\SharedKernel\Application\Validation;

interface ValidatorInterface
{
    public function validate(mixed $data): array;
}
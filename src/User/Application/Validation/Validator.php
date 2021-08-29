<?php
declare(strict_types=1);

namespace App\User\Application\Validation;

use App\SharedKernel\Application\Validation\ValidatorInterface;
use App\SharedKernel\Application\Validation\ValidationError;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface as SymfonyValidatorInterface;

class Validator implements ValidatorInterface
{
    public function __construct(private SymfonyValidatorInterface $symfonyValidator)
    {
    }

    public function validate(mixed $data): array
    {
        $violations = $this->symfonyValidator->validate($data);

        $errors = [];
        /** @var ConstraintViolationInterface $violation */
        foreach ($violations as $violation) {
            $errors[] = new ValidationError($violation->getPropertyPath(), $violation->getMessage());
        }

        return $errors;
    }
}
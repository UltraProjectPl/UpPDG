<?php
declare(strict_types=1);

namespace App\User\Infrastructure\SymfonyIntegration\Validation;

use App\SharedKernel\Application\Bus\QueryBusInterface;
use App\User\Application\Query\UserByEmail;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueEmailValidator extends ConstraintValidator
{
    public function __construct(private QueryBusInterface $queryBus)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (null === $value || '' === $value) {
            return;
        }

        if (false === $constraint instanceof UniqueEmail) {
            throw new UnexpectedTypeException($constraint, UniqueEmail::class);
        }

        $user = $this->queryBus->query(new UserByEmail($value));

        if (null !== $user) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
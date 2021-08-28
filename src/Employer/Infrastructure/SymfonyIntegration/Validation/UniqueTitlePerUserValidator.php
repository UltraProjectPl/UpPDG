<?php
declare(strict_types=1);

namespace App\Employer\Infrastructure\SymfonyIntegration\Validation;

use App\Employer\Application\Query\OfferByTitleAndUser;
use App\SharedKernel\Application\Bus\QueryBusInterface;
use App\User\Application\Security\UserContextInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueTitlePerUserValidator extends ConstraintValidator
{
    public function __construct(private UserContextInterface $userContext, private QueryBusInterface $queryBus)
    {
    }

    public function validate($value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            return;
        }

        $currentUser = $this->userContext->getCurrentUser();

        $offer = $this->queryBus->query(new OfferByTitleAndUser($value, $currentUser));

        if (null !== $offer) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
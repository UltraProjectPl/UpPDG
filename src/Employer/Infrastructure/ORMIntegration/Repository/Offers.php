<?php
declare(strict_types=1);

namespace App\Employer\Infrastructure\ORMIntegration\Repository;

use App\Employer\Domain\Offers as DomainOffers;
use App\SharedKernel\Infrastructure\ORMIntegration\Repository\EntityRepository;

final class Offers extends EntityRepository implements DomainOffers
{

}
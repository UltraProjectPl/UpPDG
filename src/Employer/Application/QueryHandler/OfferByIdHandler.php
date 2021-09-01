<?php

declare(strict_types=1);

namespace App\Employer\Application\QueryHandler;

use App\Employer\Application\Query\OfferById;
use App\Employer\Domain\Offer;
use App\Employer\Domain\Offers;
use App\SharedKernel\Application\Bus\QueryHandlerInterface;

final class OfferByIdHandler implements QueryHandlerInterface
{
    public function __construct(private Offers $offers)
    {
    }

    public function __invoke(OfferById $query): ?Offer
    {
        return $this->offers->getOfferById($query->getId());
    }
}

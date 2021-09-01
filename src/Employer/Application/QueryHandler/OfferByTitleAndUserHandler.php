<?php

declare(strict_types=1);

namespace App\Employer\Application\QueryHandler;

use App\Employer\Application\Query\OfferByTitleAndUser;
use App\Employer\Domain\Offer;
use App\Employer\Domain\Offers;
use App\SharedKernel\Application\Bus\QueryHandlerInterface;

final class OfferByTitleAndUserHandler implements QueryHandlerInterface
{
    public function __construct(private Offers $offers)
    {
    }

    public function __invoke(OfferByTitleAndUser $query): ?Offer
    {
        return $this->offers->getOfferByTitleAndUser(title: $query->getTitle(), user: $query->getUser());
    }
}

<?php

declare(strict_types=1);

namespace App\Employer\Application\QueryHandler;

use App\Employer\Application\Query\AllOffer;
use App\Employer\Domain\Offer;
use App\Employer\Domain\Offers;
use App\SharedKernel\Application\Bus\QueryHandlerInterface;

final class AllOfferHandler implements QueryHandlerInterface
{
    public function __construct(private Offers $offers)
    {
    }

    /** @return Offer[] */
    public function __invoke(AllOffer $command): array
    {
        return $this->offers->findAll();
    }
}

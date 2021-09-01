<?php
declare(strict_types=1);

namespace App\Employer\Application\Event;

use App\Employer\Domain\Offer;
use App\SharedKernel\Application\Event\EventInterface;

class OfferStateChangedEvent implements EventInterface
{

    public function __construct(private Offer $offer)
    {
    }

    public function getOffer(): Offer
    {
        return $this->offer;
    }
}
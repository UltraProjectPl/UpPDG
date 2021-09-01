<?php
declare(strict_types=1);

namespace App\Employer\Application\Subscriber;

use App\Employer\Application\Command\ExpireOffer;
use App\Employer\Domain\Offer;
use App\Employer\Domain\Offers;
use App\SharedKernel\Application\Bus\CommandBusInterface;
use App\SharedKernel\Application\Bus\EventSubscriberInterface;
use App\SharedKernel\Application\Event\DayHasPassed;
use DateTimeImmutable;

class OfferWillExpireAfterTime implements EventSubscriberInterface
{
    public function __construct(private Offers $offers, private CommandBusInterface $commandBus)
    {
    }

    public function __invoke(DayHasPassed $event): void
    {
        $offers = $this->offers->findAll();

        $now = new DateTimeImmutable();

        /** @var Offer $offer */
        foreach ($offers as $offer) {
            if (false === $offer->isActive()) {
                continue;
            }

            $passedDay = (int) $offer->getCreatedAt()->diff($now)->format('%a');
            if (null !== $offer->getUpdatedAt()) {
                $passedDay = (int) $offer->getUpdatedAt()->diff($now)->format('%a');
            }

            if ($passedDay >= 30) {
                $this->commandBus->dispatch(new ExpireOffer($offer));
            }
        }
    }
}
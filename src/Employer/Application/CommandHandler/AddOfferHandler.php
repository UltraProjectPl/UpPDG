<?php

declare(strict_types=1);

namespace App\Employer\Application\CommandHandler;

use App\Employer\Application\Command\AddOffer;
use App\Employer\Application\Event\OfferAddedEvent;
use App\Employer\Domain\Offers;
use App\SharedKernel\Application\Bus\CommandHandlerInterface;
use App\SharedKernel\Application\Bus\EventBusInterface;

final class AddOfferHandler implements CommandHandlerInterface
{
    public function __construct(private Offers $offers, private EventBusInterface $eventBus)
    {
    }

    public function __invoke(AddOffer $command): void
    {
        $this->offers->add($command->getOffer());

        $this->eventBus->dispatch(new OfferAddedEvent($command->getOffer()->getId()));
    }
}

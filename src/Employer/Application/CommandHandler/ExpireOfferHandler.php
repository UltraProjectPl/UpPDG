<?php
declare(strict_types=1);

namespace App\Employer\Application\CommandHandler;

use App\Employer\Application\Command\ExpireOffer;
use App\Employer\Application\Event\OfferStateChangedEvent;
use App\SharedKernel\Application\Bus\CommandHandlerInterface;
use App\SharedKernel\Application\Bus\EventBusInterface;

final class ExpireOfferHandler implements CommandHandlerInterface
{
    public function __construct(private EventBusInterface $eventSubscriber)
    {
    }

    public function __invoke(ExpireOffer $command)
    {
        $offer = $command->getOffer();
        $offer->deactivate();

        $this->eventSubscriber->dispatch(new OfferStateChangedEvent($offer));
    }
}
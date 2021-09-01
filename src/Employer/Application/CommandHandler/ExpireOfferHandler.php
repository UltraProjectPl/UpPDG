<?php
declare(strict_types=1);

namespace App\Employer\Application\CommandHandler;

use App\Employer\Application\Command\ExpireOffer;
use App\Employer\Application\Event\OfferStateChangedEvent;
use App\SharedKernel\Application\Bus\CommandHandlerInterface;
use App\SharedKernel\Application\Bus\EventBusInterface;
use Doctrine\ORM\EntityManagerInterface;

final class ExpireOfferHandler implements CommandHandlerInterface
{
    public function __construct(private EventBusInterface $eventBus, private EntityManagerInterface $entityManager)
    {
    }

    public function __invoke(ExpireOffer $command): void
    {
        $offer = $command->getOffer();
        $offer->deactivate();

        $this->eventBus->dispatch(new OfferStateChangedEvent($offer));

        $this->entityManager->flush();
    }
}
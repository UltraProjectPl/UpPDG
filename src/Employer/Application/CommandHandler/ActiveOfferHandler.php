<?php
declare(strict_types=1);

namespace App\Employer\Application\CommandHandler;

use App\Employer\Application\Command\ActiveOffer;
use App\Employer\Application\Event\OfferStateChangedEvent;
use App\SharedKernel\Application\Bus\CommandHandlerInterface;
use App\SharedKernel\Application\Bus\EventBusInterface;
use Doctrine\ORM\EntityManagerInterface;

final class ActiveOfferHandler implements CommandHandlerInterface
{
    public function __construct(private EventBusInterface $eventBus, private EntityManagerInterface $entityManager)
    {
    }

    public function __invoke(ActiveOffer $command): void
    {
        $offer = $command->getOffer();

        $offer->activate();

        $this->eventBus->dispatch(new OfferStateChangedEvent($offer));

        $this->entityManager->flush();
    }
}
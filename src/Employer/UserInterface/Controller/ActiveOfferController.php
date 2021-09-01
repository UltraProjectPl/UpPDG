<?php

namespace App\Employer\UserInterface\Controller;

use App\Employer\Application\Command\ActiveOffer;
use App\Employer\Application\Query\OfferById;
use App\Employer\Domain\Offer;
use App\SharedKernel\Application\Bus\CommandBusInterface;
use App\SharedKernel\Application\Bus\QueryBusInterface;
use App\SharedKernel\UserInterface\Http\ResponseFactoryInterface;
use App\User\Application\Security\UserContextInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;

final class ActiveOfferController
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private CommandBusInterface $commandBus,
        private UserContextInterface $userContext,
        private ResponseFactoryInterface $responseFactory
    ) {
    }

    public function index(string $id): Response
    {
        $id = Uuid::fromString($id);

        /** @var Offer $offer */
        $offer = $this->queryBus->query(new OfferById($id));

        if (true === $offer->isActive()) {
            return $this->responseFactory->error(["Offer '{$offer->getId()->toString()}' is already active."]);
        }

        $contextUser = $this->userContext->getCurrentUser();

        if (false === $offer->getCreator()->getId()->equals($contextUser->getId())) {
            return $this->responseFactory->error(
                ["You are not the creator of the offer '{$offer->getId()->toString()}'."]
            );
        }

        $this->commandBus->dispatch(new ActiveOffer($offer));


        return $this->responseFactory->data(['active']);
    }
}

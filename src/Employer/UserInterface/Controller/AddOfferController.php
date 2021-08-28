<?php
declare(strict_types=1);

namespace App\Employer\UserInterface\Controller;

use App\Employer\Application\Command\AddOffer;
use App\Employer\Application\Form\Dto\OfferDto;
use App\Employer\Application\Form\Type\OfferFormInterface;
use App\Employer\Application\Query\OfferById;
use App\SharedKernel\Application\Bus\CommandBusInterface;
use App\SharedKernel\Application\Bus\QueryBusInterface;
use App\SharedKernel\Application\Form\FormHandlerFactoryInterface;
use App\SharedKernel\UserInterface\Http\ResponseFactoryInterface;
use App\User\Application\Security\UserContextInterface;
use App\User\Infrastructure\SymfonyIntegration\Security\User as SecurityUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

class AddOfferController
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private FormHandlerFactoryInterface $formHandlerFactory,
        private CommandBusInterface $commandBus,
        private QueryBusInterface $queryBus,
        private UserContextInterface $userContext
    ) {
    }

    public function index(Request $request): Response
    {
        if (false === $this->userContext->isLoggedIn()) {
            return $this->responseFactory->error([], Response::HTTP_UNAUTHORIZED);
        }

        $creator = $this->userContext->getCurrentUser();

        $formHandler = $this->formHandlerFactory->createFromRequest($request, OfferFormInterface::class);

        if (false === $formHandler->isSubmissionValid()) {
            return $this->responseFactory->error($formHandler->getErrors());
        }

        /** @var OfferDto $dto */
        $dto = $formHandler->getData();

        $offer = $dto->toEntity($creator);
        $this->commandBus->dispatch(new AddOffer($offer));

        $offer = $this->queryBus->query(new OfferById($offer->getId()));

        if (null === $offer) {
            return $this->responseFactory->error('Failed created offer', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->responseFactory->create($offer);
    }
}
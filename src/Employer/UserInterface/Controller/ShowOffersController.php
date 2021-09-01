<?php

declare(strict_types=1);

namespace App\Employer\UserInterface\Controller;

use App\Employer\Application\Query\AllOffer;
use App\SharedKernel\Application\Bus\QueryBusInterface;
use App\SharedKernel\UserInterface\Http\ResponseFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ShowOffersController
{
    public function __construct(private QueryBusInterface $queryBus, private ResponseFactoryInterface $responseFactory)
    {
    }

    public function index(Request $request): Response
    {
        $offers = $this->queryBus->query(new AllOffer());

        return $this->responseFactory->data($offers);
    }
}

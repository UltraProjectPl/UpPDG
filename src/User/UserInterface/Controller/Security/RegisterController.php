<?php
declare(strict_types=1);

namespace App\User\UserInterface\Controller\Security;

use App\SharedKernel\Application\Bus\CommandBusInterface;
use App\SharedKernel\Application\Bus\QueryBusInterface;
use App\SharedKernel\Application\Form\FormHandlerFactoryInterface;
use App\SharedKernel\Application\Validation\ValidatorInterface;
use App\SharedKernel\UserInterface\Http\ResponseFactoryInterface;
use App\User\Application\Form\Dto\Security\RegisterDto;
use App\User\Application\Form\Type\Security\RegisterFormInterface;
use App\User\Application\Query\UserByEmail;
use App\User\Domain\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use RuntimeException;

final class RegisterController
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private FormHandlerFactoryInterface $formHandlerFactory,
        private CommandBusInterface $commandBus,
        private QueryBusInterface $queryBus,
    ) {
    }

    public function index(Request $request): Response
    {
        $formHandler = $this->formHandlerFactory->createFromRequest($request, RegisterFormInterface::class);

        if (false === $formHandler->isSubmissionValid()) {
            return $this->responseFactory->error($formHandler->getErrors());
        }

        /** @var RegisterDto $dto */
        $dto = $formHandler->getData();

        $this->commandBus->dispatch($dto->toCommand());

        /** @var User|null $user */
        $user = $this->queryBus->query(new UserByEmail($dto->email));

        if (null === $user) {
            throw new RuntimeException(
                sprintf('Failed to create and/or retrieve user with email: "%s"', $dto->email)
            );
        }

        return $this->responseFactory->create([
            'email' => $user->getEmail(),
        ]);
    }
}

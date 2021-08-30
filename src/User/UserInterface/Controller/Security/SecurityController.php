<?php
declare(strict_types=1);

namespace App\User\UserInterface\Controller\Security;

use App\SharedKernel\Application\Bus\CommandBusInterface;
use App\SharedKernel\Application\Bus\QueryBusInterface;
use App\SharedKernel\Application\Form\FormHandlerFactoryInterface;
use App\SharedKernel\Domain\Security\PasswordHashing;
use App\SharedKernel\UserInterface\Http\ResponseFactoryInterface;
use App\User\Application\Command\LoginUser;
use App\User\Application\Form\Dto\Security\SecurityDto;
use App\User\Application\Form\Type\Security\SecurityFormInterface;
use App\User\Application\Query\ActiveSessionByUserEmail;
use App\User\Application\Query\UserByEmail;
use App\User\Domain\Session;
use App\User\Domain\User;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class SecurityController
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
        $formHandler = $this->formHandlerFactory->createFromRequest($request, SecurityFormInterface::class);

        if (false === $formHandler->isSubmissionValid()) {
            return $this->responseFactory->error($formHandler->getErrors());
        }

        /** @var SecurityDto $dto */
        $dto = $formHandler->getData();

        $user = $this->queryBus->query(new UserByEmail($dto->email));
        if (false === $user instanceof User || false === PasswordHashing::passwordVerify($user->getPassword(), $dto->password)) {
            return $this->responseFactory->error('Invalid login data');
        }

        $this->commandBus->dispatch(new LoginUser($user, $request->getClientIp()));

        /** @var Session[] $sessions */
        $sessions = $this->queryBus->query(new ActiveSessionByUserEmail($user->getEmail()));

        if (0 === count($sessions)) {
            throw new RuntimeException(
                sprintf('Failed to authorization user with email: %s', $user->getEmail())
            );
        }

        return $this->responseFactory->authorization($sessions[0]->getToken());

    }
}
<?php
declare(strict_types=1);

namespace App\User\Application\CommandHandler;

use App\SharedKernel\Application\Bus\CommandHandlerInterface;
use App\SharedKernel\Application\Bus\EventBusInterface;
use App\User\Application\Command\LoginUser;
use App\User\Domain\Session;
use App\User\Domain\Sessions;

final class LoginUserHandler implements CommandHandlerInterface
{
    public function __construct(private Sessions $sessions, private EventBusInterface $eventBus)
    {
    }

    public function __invoke(LoginUser $command): void
    {
        $session = new Session(
            user: $command->getUser(),
            firstLoginIp: $command->getIp(),
        );

        $this->sessions->add($session);

        $this->eventBus->dispatch(UserLogged);
    }
}
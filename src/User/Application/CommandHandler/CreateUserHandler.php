<?php
declare(strict_types=1);

namespace App\User\Application\CommandHandler;

use App\SharedKernel\Application\Bus\CommandHandlerInterface;
use App\SharedKernel\Application\Bus\EventBusInterface;
use App\User\Application\Command\CreateUser;
use App\User\Application\Event\UserCreated;
use App\User\Domain\User;
use App\User\Domain\Users;

final class CreateUserHandler implements CommandHandlerInterface
{
    public function __construct(private Users $users, private EventBusInterface $eventBus)
    {
    }


    public function __invoke(CreateUser $command)
    {
        $user = new User(
            email: $command->getEmail(),
            firstName: $command->getFirstName(),
            lastName: $command->getLastName(),
            repository: $this->users,
            password: $command->getPassword(),
        );

        $this->users->add($user);

        $this->eventBus->dispatch(new UserCreated($user));
    }
}
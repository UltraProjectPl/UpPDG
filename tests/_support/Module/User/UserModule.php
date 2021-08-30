<?php
declare(strict_types=1);

namespace App\Tests\Module\User;

use App\SharedKernel\Application\Bus\CommandBusInterface;
use App\SharedKernel\Application\Bus\QueryBusInterface;
use App\SharedKernel\Domain\Security\PlainPassword;
use App\Tests\Module\SharedKernel\ContainerModule;
use App\User\Application\Command\CreateUser;
use App\User\Application\Query\UserByEmail;
use App\User\Domain\User;
use Codeception\Module;
use Codeception\Module\REST;
use Codeception\TestInterface;
use Faker\Factory;

class UserModule extends Module
{
    private QueryBusInterface $queryBus;
    private CommandBusInterface $commandBus;
    private REST $rest;

    public function _before(TestInterface $test)
    {
        /** @var ContainerModule $container */
        $container = $this->getModule(ContainerModule::class);
        $this->queryBus = $container->grabService(QueryBusInterface::class);
        $this->commandBus = $container->grabService(CommandBusInterface::class);
        $this->rest = $container->getModule('REST');
    }

    public function amLoggedIn(string $email, string $password): string
    {
        $user = $this->queryUserByEmail($email);
        if (null === $user) {
            $user = $this->haveCreatedUser($email, $password);
        }

        $this->rest->haveHttpHeader('Accept', 'application/json');
        $this->rest->haveHttpHeader('X-Requested-With', 'XMLHttpRequest');
        $this->rest->sendPOST('/auth/login', [
            'email' => $email,
            'password' => $password,
        ]);

        $result = json_decode($this->rest->grabResponse(), true);

        return $result['token'];
    }

    public function haveCreatedUser(
        ?string $email = null,
        ?string $password = null,
        ?string $firstName = null,
        ?string $lastName = null,
    ): User {
        $faker = Factory::create('pl_PL');
        $command = new CreateUser(
            email: $email ?? $faker->email,
            firstName: $firstName ?? $faker->firstName(),
            lastName: $lastName ?? $faker->lastName(),
            password: new PlainPassword($password ?? $faker->password(6)),
        );

        $this->commandBus->dispatch($command);

        $user = $this->queryUserByEmail($command->getEmail());
        return $user;
    }

    public function seeUserForEmailDoesNotExist(string $email): void
    {
        $user = $this->queryUserByEmail($email);
        $this->assertNull($user);
    }

    private function queryUserByEmail(string $email): ?User
    {
        return $this->queryBus->query(new UserByEmail($email));
    }
}
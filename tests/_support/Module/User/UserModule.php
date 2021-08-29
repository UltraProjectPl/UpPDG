<?php
declare(strict_types=1);

namespace App\Tests\_support\Module\User;

use App\SharedKernel\Application\Bus\CommandBusInterface;
use App\SharedKernel\Application\Bus\QueryBusInterface;
use App\Tests\Module\SharedKernel\ContainerModule;
use App\User\Application\Query\UserByEmail;
use App\User\Domain\User;
use Codeception\Module;
use Codeception\TestInterface;

class UserModule extends Module
{
    private QueryBusInterface $queryBus;

    public function _before(TestInterface $test)
    {
        /** @var ContainerModule $container */
        $container = $this->getModule(ContainerModule::class);
        $this->queryBus = $container->grabTestService(CommandBusInterface::class);
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
<?php
declare(strict_types=1);

namespace App\User\Infrastructure\SymfonyIntegration\DataFixtures;

use App\SharedKernel\Application\Bus\CommandBusInterface;
use App\SharedKernel\Application\Bus\QueryBusInterface;
use App\SharedKernel\Domain\Security\PlainPassword;
use App\SharedKernel\Infrastructure\SymfonyIntegration\DataFixtures\FixtureInterface;
use App\User\Application\Command\CreateUser;
use App\User\Application\Query\UserByEmail;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use Doctrine\Common\DataFixtures\SharedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class LoadUserData implements FixtureInterface, SharedFixtureInterface, FixtureGroupInterface
{
    private const COUNT = 10;

    private ReferenceRepository $referenceRepository;

    public static function getGroups(): array
    {
        return ['dev'];
    }

    public function __construct(private CommandBusInterface $commandBus, private QueryBusInterface $queryBus)
    {
    }

    public function setReferenceRepository(ReferenceRepository $referenceRepository): void
    {
        $this->referenceRepository = $referenceRepository;
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < self::COUNT; $i++) {
            $password = new PlainPassword('password');
            $email = "user$i@gmail.com";
            $name = "user$i";
            $surname = "surname";

            $this->commandBus->dispatch(new CreateUser($email, $name, $surname, $password));
            $user = $this->queryBus->query(new UserByEmail($email));

            $this->referenceRepository->setReference("user-$i", $user);
        }
    }
}
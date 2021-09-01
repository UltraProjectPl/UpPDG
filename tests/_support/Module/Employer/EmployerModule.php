<?php

namespace App\Tests\Module\Employer;

use App\Employer\Application\Command\AddOffer;
use App\Employer\Application\Form\Dto\PaymentSpreadsDto;
use App\Employer\Application\Query\OfferById;
use App\Employer\Domain\Offer;
use App\Employer\Domain\PaymentSpreads;
use App\SharedKernel\Application\Bus\CommandBusInterface;
use App\SharedKernel\Application\Bus\QueryBusInterface;
use App\Tests\Module\SharedKernel\ContainerModule;
use App\User\Application\Query\SessionByToken;
use App\User\Domain\Session;
use App\User\Domain\User;
use Codeception\Module;
use Codeception\TestInterface;
use Faker\Factory;
use Ramsey\Uuid\UuidInterface;

class EmployerModule extends Module
{
    private QueryBusInterface $queryBus;
    private CommandBusInterface $commandBus;

    public function _before(TestInterface $test)
    {
        /** @var ContainerModule $container */
        $container = $this->getModule(ContainerModule::class);

        $this->queryBus = $container->grabService(QueryBusInterface::class);
        $this->commandBus = $container->grabService(CommandBusInterface::class);
    }

    public function seeOfferHasActive(UuidInterface $id): void
    {
        /** @var Offer $offer */
        $offer = $this->queryBus->query(new OfferById($id));
        $this->assertNotNull($offer);
        $this->assertTrue($offer->isActive());
    }

    public function haveCreatedOffer(
        string $token,
        ?string $title = null,
        ?string $companyName = null,
        ?PaymentSpreads $paymentSpreads = null,
        ?string $city = null,
        ?bool $remoteWorkPossible = null,
        ?bool $remoteWorkOnly = null,
        ?bool $active = null,
        ?string $nip = null,
        ?string $tin = null,
    ): Offer {
        $faker = Factory::create('pl_PL');

        if (null === $paymentSpreads) {
            $paymentSpreads = new PaymentSpreadsDto();
            $paymentSpreads->min = $faker->randomElement([3000, 4000, 5000]);
            $paymentSpreads->max = $faker->randomElement([7000, 10000, 15000]);
            $paymentSpreads->currency = $faker->currencyCode();
        }

        $offer = new Offer(
            creator: $this->getUserByToken($token),
            title: $title ?? $faker->sentence(),
            companyName: $companyName ?? $faker->company(),
            paymentSpreads: $paymentSpreads->toEntity(),
            city: $city ?? $faker->city(),
            remoteWorkPossible: $remoteWorkPossible ?? $faker->boolean(),
            remoteWorkOnly: $remoteWorkOnly ?? $faker->boolean(),
            active: $active ?? $faker->boolean(95),
            nip: $nip,
            tin: $tin,
        );

        $this->commandBus->dispatch(new AddOffer($offer));

        return $offer;
    }

    private function getUserByToken(?string $token): User
    {
        /** @var Session $session */
        $session = $this->queryBus->query(new SessionByToken($token));
        $this->assertNotNull($session);

        return $session->getUser();
    }
}
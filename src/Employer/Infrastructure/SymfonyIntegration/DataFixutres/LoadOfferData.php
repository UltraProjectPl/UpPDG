<?php

declare(strict_types=1);

namespace App\Employer\Infrastructure\SymfonyIntegration\DataFixutres;

use App\Employer\Application\Command\AddOffer;
use App\Employer\Application\Form\Dto\PaymentSpreadsDto;
use App\Employer\Application\Query\OfferById;
use App\Employer\Domain\Offer;
use App\Employer\Domain\PaymentSpreads;
use App\SharedKernel\Application\Bus\CommandBusInterface;
use App\SharedKernel\Application\Bus\QueryBusInterface;
use App\SharedKernel\Domain\Security\PlainPassword;
use App\SharedKernel\Infrastructure\SymfonyIntegration\DataFixtures\FixtureInterface;
use App\User\Application\Query\UserByEmail;
use App\User\Domain\User;
use App\User\Infrastructure\SymfonyIntegration\DataFixtures\LoadUserData;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use Doctrine\Common\DataFixtures\SharedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

final class LoadOfferData implements
    FixtureInterface,
    SharedFixtureInterface,
    DependentFixtureInterface,
    FixtureGroupInterface
{
    private const COUNT = 20;

    private ReferenceRepository $referenceRepository;

    public function __construct(private CommandBusInterface $commandBus, private QueryBusInterface $queryBus)
    {
    }

    public static function getGroups(): array
    {
        return ['dev'];
    }

    public function setReferenceRepository(ReferenceRepository $referenceRepository): void
    {
        $this->referenceRepository = $referenceRepository;
    }

    public function getDependencies(): array
    {
        return [LoadUserData::class];
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('PL_pl');

        for ($i = 0; $i < self::COUNT; $i++) {
            /** @var User $user */
            $user = $this->referenceRepository->getReference('user-' . random_int(0, 9));

            $paymentSpreads = new PaymentSpreadsDto();
            $paymentSpreads->min = $faker->randomElement([3000, 4000, 5000]);
            $paymentSpreads->max = $faker->randomElement([7000, 10000, 15000]);
            /** @var non-empty-string $nonEmptyString */
            $nonEmptyString = $faker->currencyCode();
            $paymentSpreads->currency = $nonEmptyString;

            $offer = new Offer(
                creator: $user,
                title: $faker->sentence(),
                companyName: $faker->company(),
                paymentSpreads: $paymentSpreads->toEntity(),
                city: $faker->city(),
                remoteWorkPossible: $faker->boolean(),
                remoteWorkOnly: $faker->boolean(),
                active: $faker->boolean(95),
            );

            $this->commandBus->dispatch(new AddOffer($offer));
            $offer = $this->queryBus->query(new OfferById($offer->getId()));

            $this->referenceRepository->setReference("offer-$i", $offer);
        }
    }
}

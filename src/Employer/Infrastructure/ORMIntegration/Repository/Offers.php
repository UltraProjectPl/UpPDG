<?php
declare(strict_types=1);

namespace App\Employer\Infrastructure\ORMIntegration\Repository;

use App\Employer\Domain\Offer;
use App\Employer\Domain\Offers as DomainOffers;
use App\SharedKernel\Infrastructure\ORMIntegration\Repository\EntityRepository;
use App\User\Domain\User;
use Ramsey\Uuid\UuidInterface;

final class Offers extends EntityRepository implements DomainOffers
{
    public function add(Offer $offer): void
    {
        $this->persistEntity($offer);
    }

    /** @return Offer[] */
    public function findAll(): array
    {
        return $this->getORMRepository(Offer::class)->findAll();
    }

    public function getOfferById(UuidInterface $id): ?Offer
    {
        return $this->getORMRepository(Offer::class)->findOneBy(['id' => $id]);
    }

    public function getOfferByTitleAndUser(string $title, User $user): ?Offer
    {
        return $this->getORMRepository(Offer::class)->findOneBy([
            'title' => $title,
            'creator' => $user,
        ]);
    }
}
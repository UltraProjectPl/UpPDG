<?php
declare(strict_types=1);

namespace App\Employer\Domain;

use App\User\Domain\User;
use Ramsey\Uuid\UuidInterface;

interface Offers
{
    public function add(Offer $offer): void;

    /** @return Offer[] */
    public function findAll(): array;

    public function getOfferById(UuidInterface $id): ?Offer;

    public function getOfferByTitleAndUser(string $title, User $user): ?Offer;
}
<?php
declare(strict_types=1);

namespace App\Employer\Application\Form\Dto;

use App\Employer\Domain\Offer;
use App\User\Domain\User;

final class OfferDto
{
    public ?string $title = null;
    public ?string $companyName = null;
    public ?PaymentSpreadsDto $paymentSpreads = null;
    public ?string $city = null;
    public ?bool $remoteWorkPossible = null;
    public ?bool $remoteWorkOnly = null;
    public ?string $nip = null;
    public ?string $tin = null;

    public function toEntity(User $user): Offer
    {
        return new Offer(
            creator: $user,
            title: $this->title,
            companyName: $this->companyName,
            paymentSpreads: $this->paymentSpreads->toEntity(),
            city: $this->city,
            remoteWorkPossible: $this->remoteWorkPossible,
            remoteWorkOnly: $this->remoteWorkOnly,
            nip: $this->nip,
            tin: $this->tin,
        );
    }
}
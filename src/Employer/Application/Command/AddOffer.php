<?php

declare(strict_types=1);

namespace App\Employer\Application\Command;

use App\Employer\Domain\Offer;
use App\SharedKernel\Application\Command\CommandInterface;

final class AddOffer implements CommandInterface
{
    public function __construct(private Offer $offer)
    {
    }

    public function getOffer(): Offer
    {
        return $this->offer;
    }
}

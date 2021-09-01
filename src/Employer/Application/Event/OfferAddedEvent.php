<?php

declare(strict_types=1);

namespace App\Employer\Application\Event;

use App\SharedKernel\Application\Event\EventInterface;
use Ramsey\Uuid\UuidInterface;

final class OfferAddedEvent implements EventInterface
{
    public function __construct(private UuidInterface $id)
    {
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }
}

<?php
declare(strict_types=1);

namespace App\Employer\Application\Query;

use App\SharedKernel\Application\Query\QueryInterface;
use Ramsey\Uuid\UuidInterface;

final class OfferById implements QueryInterface
{
    public function __construct(private UuidInterface $id)
    {
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }
}
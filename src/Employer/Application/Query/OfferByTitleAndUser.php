<?php

declare(strict_types=1);

namespace App\Employer\Application\Query;

use App\SharedKernel\Application\Query\QueryInterface;
use App\User\Domain\User;

final class OfferByTitleAndUser implements QueryInterface
{
    public function __construct(private string $title, private User $user)
    {
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}

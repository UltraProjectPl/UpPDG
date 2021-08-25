<?php
declare(strict_types=1);

namespace App\User\Domain;

interface Sessions
{
    public function add(Session $session): void;

    /** @return Session[] */
    public function findOneByActiveUserEmail(string $email): array;

    public function findOneByToken(string $token): ?Session;
}
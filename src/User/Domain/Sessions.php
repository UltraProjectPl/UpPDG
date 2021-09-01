<?php
declare(strict_types=1);

namespace App\User\Domain;

interface Sessions
{
    public function add(Session $session): void;

    /** @return Session[] */
    public function findByActiveUserEmail(string $email): array;

    public function findByToken(string $token): ?Session;
}
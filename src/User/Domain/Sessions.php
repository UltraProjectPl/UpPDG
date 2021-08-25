<?php
declare(strict_types=1);

namespace App\User\Domain;

interface Sessions
{
    public function add(Session $session): void;

    public function findOneByActiveUserEmail(string $email): ?Session;

    public function findOneByToken(string $token): ?Session;
}
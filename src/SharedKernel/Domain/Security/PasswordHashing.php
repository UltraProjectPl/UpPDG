<?php

declare(strict_types=1);

namespace App\SharedKernel\Domain\Security;

final class PasswordHashing
{
    public static function passwordHash(PlainPassword $plainPassword): string
    {
        return password_hash((string) $plainPassword, PASSWORD_BCRYPT);
    }

    public static function passwordVerify(string $passwordHash, string $plainPassword): bool
    {
        return password_verify($plainPassword, $passwordHash);
    }
}

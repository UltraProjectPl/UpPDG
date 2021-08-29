<?php
declare(strict_types=1);

namespace App\SharedKernel\Domain\Security;

use RuntimeException;

final class PasswordHashing
{
    public static function passwordHash(PlainPassword $plainPassword): string
    {
        $passwordHash = password_hash((string) $plainPassword, PASSWORD_BCRYPT);

        if (false === $passwordHash || null === $passwordHash) {
            throw new RuntimeException('Unable to encode password');
        }

        return $passwordHash;
    }

    public static function passwordVerify(string $passwordHash, string $plainPassword): bool
    {
        return password_verify($plainPassword, $passwordHash);
    }
}
<?php
declare(strict_types=1);

namespace App\Tests\unit\SharedKernel\Domain\Security;

use App\SharedKernel\Domain\Security\PasswordHashing;
use App\SharedKernel\Domain\Security\PlainPassword;
use Codeception\Test\Unit;

class PasswordHashingTest extends Unit
{
    public function testPasswordHash(): void
    {
        $password = new PlainPassword('123456');

        $hashPassword = PasswordHashing::passwordHash($password);

        $this->assertNotEquals((string) $password, $hashPassword);
    }

    public function testPass(): void
    {
        $password = new PlainPassword('123456');

        $hashPassword = PasswordHashing::passwordHash($password);

        $this->assertTrue(PasswordHashing::passwordVerify($hashPassword, (string) $password));
        $this->assertFalse(PasswordHashing::passwordVerify($hashPassword, (string) 'test'));
    }
}
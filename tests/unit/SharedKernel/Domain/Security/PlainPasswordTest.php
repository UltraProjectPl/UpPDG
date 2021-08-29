<?php
declare(strict_types=1);

namespace App\Tests\unit\SharedKernel\Domain\Security;

use App\SharedKernel\Domain\Security\PlainPassword;
use Codeception\Test\Unit;
use InvalidArgumentException;

class PlainPasswordTest extends Unit
{
    public function testEmptyValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectDeprecationMessage('Provided password value is too short (0)');

        new PlainPassword('');
    }

    public function testToShortPlainValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectDeprecationMessage('Provided password value is too short (5)');

        new PlainPassword('12345');
    }

    public function testToLongPlainValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectDeprecationMessage('Provided password value is too long (33)');

        new PlainPassword(str_repeat('123', 11));
    }

    public function testProperValue(): void
    {
        $password = new PlainPassword('123456');
        $this->assertEquals('123456', (string) $password);
    }
}
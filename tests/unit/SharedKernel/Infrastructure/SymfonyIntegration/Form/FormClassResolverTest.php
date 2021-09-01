<?php

declare(strict_types=1);

namespace App\Tests\unit\SharedKernel\Infrastructure\SymfonyIntegration\Form;

use App\SharedKernel\Application\Form\FormInterface;
use App\SharedKernel\Infrastructure\SymfonyIntegration\Form\FormClassResolver;
use App\User\Application\Form\Type\Security\RegisterFormInterface;
use Codeception\Test\Unit;
use InvalidArgumentException;

class FormClassResolverTest extends Unit
{
    public function testNotSupportedClass(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectDeprecationMessage('"App\User\Application\Form\Type\Security\RegisterFormInterface" does not have a corresponding Symfony form');

        $resolver = new FormClassResolver([$this->createMock(FormInterface::class)]);
        $resolver->resolve(RegisterFormInterface::class);
    }

    public function testSupportedClass(): void
    {
        $mock = $this->createMock(RegisterFormInterface::class);
        $resolve = new FormClassResolver([$mock]);

        $this->assertEquals(get_class($mock), $resolve->resolve(RegisterFormInterface::class));
    }
}

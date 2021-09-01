<?php

declare(strict_types=1);

namespace App\Tests\unit\User\Domain;

use App\SharedKernel\Domain\Security\PlainPassword;
use App\User\Domain\User;
use App\User\Domain\Users;
use Codeception\Test\Unit;

class UserTest extends Unit
{
    public function testCreation(): void
    {
        $password = new PlainPassword('test123');
        $users = $this->makeEmpty(Users::class);
        $user = new User('test@email.com', 'Name', 'Surname', $users, $password);

        $this->assertEquals('test@email.com', $user->getEmail());
        $this->assertEquals('Name', $user->getFirstName());
        $this->assertEquals('Surname', $user->getLastName());
        $this->assertNotEquals('test', $user->getPassword());
    }

    public function testUniqueSlugGeneration(): void
    {
        $firstName = 'name';
        $lastName = 'surname';
        $users = $this->createMock(Users::class);
        $users->expects($this->once())->method('getUniqueSlug')
            ->with($this->isInstanceOf(User::class))
            ->willReturn('name.surname2')
        ;

        $user = new User('test@email.com', $firstName, $lastName, $users, new PlainPassword('test123'));
        $this->assertEquals('name.surname2', $user->getSlug());
    }

    public function testSerialize(): void
    {
        $firstName = 'name';
        $lastName = 'surname';
        $users = $this->createMock(Users::class);
        $users->expects($this->once())->method('getUniqueSlug')
            ->with($this->isInstanceOf(User::class))
            ->willReturn('name.surname2')
        ;

        $user = new User('test@email.com', $firstName, $lastName, $users, new PlainPassword('test123'));

        $this->assertEquals(json_decode(json_encode($user), true), [
            'id' => $user->getId()->toString(),
            'email' => $user->getEmail(),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'password' => $user->getPassword(),
            'slug' => $user->getSlug(),
            'createdAt' => (array) $user->getCreatedAt(),
            'updatedAt' => $user->getUpdatedAt(),
            'deletedAt' => $user->getDeletedAt(),
        ]);
    }
}

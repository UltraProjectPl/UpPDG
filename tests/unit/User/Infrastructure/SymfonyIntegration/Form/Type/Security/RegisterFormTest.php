<?php

declare(strict_types=1);

namespace App\Tests\unit\User\Infrastructure\SymfonyIntegration\Form\Type\Security;

use App\Tests\UnitTester;
use App\User\Infrastructure\SymfonyIntegration\Form\Type\Security\RegisterForm;
use Codeception\Test\Unit;

class RegisterFormTest extends Unit
{
    protected UnitTester $tester;

    public function testEmptySubmission(): void
    {
        $form = $this->tester->createForm(RegisterForm::class);
        $form->submit([]);

        $this->assertFalse($form->isValid());

        $this->tester->assertFormFieldsBlankViolation(
            $form,
            [
                'email' => null,
                'firstName' => null,
                'lastName' => null,
                'password' => null,
            ]
        );
    }

    public function testTooShortPassword(): void
    {
        $form = $this->tester->createForm(RegisterForm::class);
        $form->submit(['password' => '12345']);

        $this->assertFalse($form->isValid());

        $this->tester->assertFieldHasErrorMessage($form->get('password'), 'This value is too short. It should have 6 characters or more.');
    }

    public function testTooLongPassword(): void
    {
        $form = $this->tester->createForm(RegisterForm::class);
        $form->submit(['password' => str_repeat('123', 11)]);

        $this->assertFalse($form->isValid());

        $this->tester->assertFieldHasErrorMessage($form->get('password'), 'This value is too long. It should have 32 characters or less.');
    }

    public function testInvalidSubmit(): void
    {
        $tooLongString = $this->tester->createTooLongString();
        $form = $this->tester->createForm(RegisterForm::class);
        $form->submit([
            'firstName' => $tooLongString,
            'lastName' => $tooLongString,
        ]);

        $this->tester->assertFieldHasErrorMessage($form->get('firstName'), 'This value is too long. It should have 255 characters or less.');
        $this->tester->assertFieldHasErrorMessage($form->get('lastName'), 'This value is too long. It should have 255 characters or less.');

        $this->tester->assertFormFieldsInvalid($form, [
            'email' => null,
            'firstName' => null,
            'lastName' => null,
        ]);
    }
}

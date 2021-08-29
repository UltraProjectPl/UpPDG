<?php
declare(strict_types=1);

namespace App\Tests\unit\User\Infrastructure\SymfonyIntegration\Form\Type\Security;

use App\Tests\UnitTester;
use App\User\Infrastructure\SymfonyIntegration\Form\Type\Security\SecurityForm;
use Codeception\Test\Unit;

class SecurityFormTest extends Unit
{
    protected UnitTester $tester;

    public function testEmptySubmission(): void
    {
        $form = $this->tester->createForm(SecurityForm::class);
        $form->submit([]);

        $this->assertFalse($form->isValid());

        $this->tester->assertFormFieldsBlankViolation(
            $form,
            [
                'email' => null,
                'password' => null,
            ]
        );
    }

    public function testTooShortPassword(): void
    {
        $form = $this->tester->createForm(SecurityForm::class);
        $form->submit(['password' => '12345']);

        $this->assertFalse($form->isValid());

        $this->tester->assertFieldHasErrorMessage($form->get('password'), 'This value is too short. It should have 6 characters or more.');
    }

    public function testTooLongPassword(): void
    {
        $form = $this->tester->createForm(SecurityForm::class);
        $form->submit(['password' => str_repeat('123', 11)]);

        $this->assertFalse($form->isValid());

        $this->tester->assertFieldHasErrorMessage($form->get('password'), 'This value is too long. It should have 32 characters or less.');
    }

    public function testInvalidSubmit(): void
    {
        $form = $this->tester->createForm(SecurityForm::class);
        $form->submit([
            'email' => 'invalid email',
        ]);

        $this->tester->assertFieldHasErrorMessage($form->get('email'), 'This value is not a valid email address.');

        $this->tester->assertFormFieldsInvalid($form, [
            'email' => null,
        ]);
    }
}
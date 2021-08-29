<?php
declare(strict_types=1);

namespace App\Tests\unit\Employer\Infrastructure\SymfonyIntegration\Form\Type;

use App\Employer\Infrastructure\SymfonyIntegration\Form\Type\PaymentSpreadsType;
use App\Tests\UnitTester;
use Codeception\Test\Unit;

class PaymentSpreadsTypeTest extends Unit
{
    protected UnitTester $tester;

    public function testEmptySubmission(): void
    {
        $form = $this->tester->createForm(PaymentSpreadsType::class);
        $form->submit([]);

        $this->assertFalse($form->isValid());

        $this->tester->assertFormFieldsBlankViolation(
            $form,
            [
                'min' => null,
                'max' => null,
                'currency' => null,
            ]
        );
    }

    public function testInvalidSubmit(): void
    {
        $form = $this->tester->createForm(PaymentSpreadsType::class);
        $form->submit([
            'min' => 'bad type',
            'max' => 'bad type',
            'currency' => 'FAILED'
        ]);

        $this->tester->assertFieldHasErrorMessage($form->get('currency'), 'This value should have exactly 3 characters.');

        $this->tester->assertFormFieldsInvalid($form, [
            'min' => null,
            'max' => null,
            'currency' => null,
        ]);
    }
}
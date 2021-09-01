<?php

declare(strict_types=1);

namespace App\Tests\unit\Employer\Infrastructure\SymfonyIntegration\Form\Type;

use App\Employer\Infrastructure\SymfonyIntegration\Form\Type\OfferForm;
use App\Tests\UnitTester;
use Codeception\Test\Unit;

class OfferFormTest extends Unit
{
    protected UnitTester $tester;

    public function testEmptySubmission(): void
    {
        $form = $this->tester->createForm(OfferForm::class);
        $form->submit([]);

        $this->assertFalse($form->isValid());

        $this->tester->assertFormFieldsBlankViolation(
            $form,
            [
                'title' => null,
                'companyName' => null,
                'city' => null,
            ]
        );
    }


    public function testInvalidSubmit(): void
    {
        $tooLongString = $this->tester->createTooLongString();
        $form = $this->tester->createForm(OfferForm::class);
        $form->submit([
            'companyName' => $tooLongString,
            'city' => $tooLongString,
        ]);

        $this->tester->assertFormFieldsInvalid($form, [
            'companyName' => null,
            'city' => null,
        ]);
    }
}

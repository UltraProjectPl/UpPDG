<?php

declare(strict_types=1);

namespace App\Tests\Module\SharedKernel;

use Codeception\Module;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class FormValidationModule extends Module
{
    public function createForm(string $class, ?array $data = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(
            $class,
            $data,
            array_merge($options, ['csrf_protection' => false])
        );
    }

    public function assertFormFieldsBlankViolation(FormInterface $form, array $data): void
    {
        $this->assertTrue($form->isSynchronized());
        $this->assertFalse($form->isValid());
        foreach ($data as $key => $value) {
            if (true === is_array($value)) {
                $this->assertFormFieldsBlankViolation($form->get($key), $value);
                continue;
            }

            $this->assertFieldHasNotBlankError($form->get($key));
        }
    }

    public function assertFieldHasNotBlankError(FormInterface $formField): void
    {
        $this->assertFalse($formField->isValid());
        $formError = $formField->getErrors()->current();
        $this->assertNotFalse($formError);
        $this->assertEquals('This value should not be blank.', $formError->getMessageTemplate());
    }

    public function assertFormFieldsInvalid(FormInterface $form, array $data): void
    {
        $this->assertTrue($form->isSynchronized());
        $this->assertFalse($form->isValid());

        foreach ($data as $key => $value) {
            if (true === is_array($value)) {
                $this->assertFormFieldsInvalid($form->get($key), $value);
                continue;
            }

            $this->assertFieldIsInvalid($form->get($key));
        }
    }

    public function assertFieldIsInvalid(FormInterface $formField): void
    {
        $this->assertFalse($formField->isValid(), sprintf('Key "%s" is valid', $formField->getName()));
    }

    public function assertFieldHasErrorMessage(FormInterface $formField, string $message): void
    {
        $this->assertFieldIsInvalid($formField);
        $this->assertEquals($message, $formField->getErrors()->current()->getMessage());
    }

    public function createTooLongString(): string
    {
        return bin2hex(random_bytes(128));
    }

    private function getFormFactory(): FormFactoryInterface
    {
        return $this->getSymfony()->grabService('form.factory');
    }

    private function getSymfony(): SymfonyModule
    {
        return $this->getModule(SymfonyModule::class);
    }
}

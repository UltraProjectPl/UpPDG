<?php

declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\SymfonyIntegration\Form;

use App\SharedKernel\Application\Form\FormHandlerInterface;
use App\SharedKernel\Application\Form\FormViewInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

final class FormHandler implements FormHandlerInterface
{
    /** @param FormInterface<FormInterface> $form */
    public function __construct(private FormInterface $form, Request $request)
    {
        $this->form->submit($request->request->all());
    }

    public function isSubmissionValid(): bool
    {
        return true === $this->form->isSubmitted() && true === $this->form->isValid();
    }

    public function getData(): mixed
    {
        return $this->form->getData();
    }

    public function createView(): FormViewInterface
    {
        return new FormView($this->form->createView());
    }

    /** @return array<string, string[]>> */
    public function getErrors(): array
    {
        $errors = [];

        foreach ($this->form->all() as $field) {
            /** @var FormError $error */
            foreach ($field->getErrors(true) as $error) {
                $errors[$field->getName()][] = $error->getMessage();
            }
        }

        return $errors;
    }
}

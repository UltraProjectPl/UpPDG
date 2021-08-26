<?php
declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\SymfonyIntegration\Form;

use App\SharedKernel\Application\Form\FormHandlerInterface;
use App\SharedKernel\Application\Form\FormViewInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

final class FormHandler implements FormHandlerInterface
{
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

    public function getErrors(): array
    {
        $errors = [];

        foreach ($this->form->all() as $field) {
            foreach ($field->getErrors(true) as $error) {
                $errors[$field->getName()][] = $error->getMessage();
            }
        }

        return $errors;
    }
}
<?php
declare(strict_types=1);

namespace App\SharedKernel\Application\Form;

interface FormHandlerInterface
{
    public function isSubmissionValid(): bool;
    public function getData(): mixed;
    public function createView(): FormViewInterface;

    /** @return array<string, string[]>> */
    public function getErrors(): array;
}
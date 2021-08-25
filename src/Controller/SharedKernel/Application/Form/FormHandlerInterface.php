<?php
declare(strict_types=1);

namespace App\Controller\SharedKernel\Application\Form;

interface FormHandlerInterface
{
    public function isSubmissionValid(): bool;
    public function getData(): mixed;
    public function createView(): FormViewInterface;
}
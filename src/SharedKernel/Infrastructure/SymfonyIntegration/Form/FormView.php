<?php
declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\SymfonyIntegration\Form;

use App\SharedKernel\Application\Form\FormViewInterface;
use Symfony\Component\Form\FormView as SymfonyFormView;

final class FormView extends SymfonyFormView implements FormViewInterface
{
    public function __construct(SymfonyFormView $formView)
    {
        SymfonyFormView::__construct();
        $this->vars = $formView->vars;
        $this->parent = $formView->parent;
        $this->children = $formView->children;
    }
}
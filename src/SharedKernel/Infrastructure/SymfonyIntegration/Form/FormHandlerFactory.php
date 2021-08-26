<?php
declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\SymfonyIntegration\Form;

use App\SharedKernel\Application\Form\FormHandlerFactoryInterface;
use App\SharedKernel\Application\Form\FormHandlerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

final class FormHandlerFactory implements FormHandlerFactoryInterface
{
    public function __construct(private FormFactoryInterface $formFactory, private FormClassResolver $formClassResolver)
    {
    }

    public function createFromRequest(
        Request $request,
        string $type,
        mixed $data = null,
        array $options = []
    ): FormHandlerInterface {
        return new FormHandler(
            $this->formFactory->create(
                $this->getFormClass($type),
                $data,
                $options,
            ),
            $request,
        );
    }

    private function getFormClass(string $type): string
    {
        return $this->formClassResolver->resolve($type);
    }
}
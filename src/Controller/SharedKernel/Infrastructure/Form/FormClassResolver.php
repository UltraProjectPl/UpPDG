<?php
declare(strict_types=1);

namespace App\Controller\SharedKernel\Infrastructure\Form;

use App\Controller\SharedKernel\Application\Form\FormInterface;
use InvalidArgumentException;

class FormClassResolver
{
    public function __construct(private array $forms)
    {
    }

    public function resolve(string $type): string
    {
        $form = array_reduce(
            $this->forms,
            fn (?FormInterface $accumulator, FormInterface $form): ?FormInterface => true === $form instanceof $type ? $form : $accumulator
        );

        if (null === $form) {
            throw new InvalidArgumentException(sprintf(
                '"%s" does not have a corresponding Symfony form.',
                $type
            ));
        }

        return get_class($form);
    }
}
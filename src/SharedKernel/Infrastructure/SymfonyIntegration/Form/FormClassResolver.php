<?php
declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\SymfonyIntegration\Form;

use App\SharedKernel\Application\Form\FormInterface;
use InvalidArgumentException;

final class FormClassResolver
{
    public function __construct(private array $forms)
    {
    }

    public function resolve(string $type): string
    {
        $form = array_reduce(
            $this->forms,
            static function (?FormInterface $accumulator, FormInterface $form) use ($type): ?FormInterface {
                return true === $form instanceof $type ? $form : $accumulator;
            }
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
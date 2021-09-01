<?php

declare(strict_types=1);

namespace App\SharedKernel\Application\Form;

interface FormHandlerFactoryInterface
{
    /** @param array<string, string> $options */
    public function createFromRequest(
        object $request,
        string $type,
        mixed $data = null,
        array $options = []
    ): FormHandlerInterface;
}

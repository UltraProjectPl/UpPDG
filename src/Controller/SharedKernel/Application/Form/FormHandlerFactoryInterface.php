<?php
declare(strict_types=1);

namespace App\Controller\SharedKernel\Application\Form;

use Symfony\Component\HttpFoundation\Request;

interface FormHandlerFactoryInterface
{
    public function createWithRequest(?Request $request, string $type, mixed $data = null, array $options = []): FormHandlerInterface;

    public function createWithName(string $name, string $type, mixed $data = null, array $options = []): FormHandlerInterface;
}
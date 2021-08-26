<?php
declare(strict_types=1);

namespace App\SharedKernel\Application\Form;

use Symfony\Component\HttpFoundation\Request;

interface FormHandlerFactoryInterface
{
    public function createFromRequest(Request $request, string $type, mixed $data = null, array $options = []): FormHandlerInterface;

}
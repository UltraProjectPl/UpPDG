<?php
declare(strict_types=1);

namespace App\User\Application\Form\DTO\Security;

use App\SharedKernel\Application\Command\CommandInterface;
use App\SharedKernel\Application\Form\Dto\DtoInterface;

final class SecurityDto
{
    public ?string $email = null;
    public ?string $password = null;
}
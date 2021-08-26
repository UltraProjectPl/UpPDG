<?php
declare(strict_types=1);

namespace App\User\Application\Form\Dto\Security;

final class SecurityDto
{
    public ?string $email = null;
    public ?string $password = null;
}
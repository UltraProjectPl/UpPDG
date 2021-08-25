<?php
declare(strict_types=1);

namespace App\User\Application\QueryHandler;

use App\SharedKernel\Application\Bus\QueryHandlerInterface;
use App\User\Application\Query\ActiveSessionByUserEmail;
use App\User\Domain\Session;
use App\User\Domain\Sessions;
use JetBrains\PhpStorm\ArrayShape;

final class ActiveSessionByUserEmailHandler implements QueryHandlerInterface
{
    public function __construct(private Sessions $sessions)
    {
    }

    public function __invoke(ActiveSessionByUserEmail $query): ?Session
    {
        return $this->sessions->findOneByActiveUserEmail($query->getEmail());
    }
}
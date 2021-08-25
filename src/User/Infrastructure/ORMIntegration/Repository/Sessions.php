<?php
declare(strict_types=1);

namespace App\User\Infrastructure\ORMIntegration\Repository;

use App\SharedKernel\Infrastructure\ORMIntegration\Repository\EntityRepository;
use App\User\Domain\Session;
use Doctrine\ORM\Query\Expr\Join;
use App\User\Domain\Sessions as DomainSessions;

class Sessions extends EntityRepository implements DomainSessions
{
    public function add(Session $session): void
    {
        $this->persistEntity($session);
    }

    public function findOneByActiveUserEmail(string $email): ?Session
    {
        return $this
            ->getORMRepository(Session::class)
            ->createQueryBuilder('s')
            ->innerJoin('s.user', 'u', Join::WITH, 'u.email = :email')
            ->andWhere('s.token IS NOT null')
            ->setParameters(['email' => $email])
            ->getQuery()
            ->getResult()
        ;
    }

    public function findOneByToken(string $token): ?Session
    {
        return $this
            ->getORMRepository(Session::class)
            ->createQueryBuilder('s')
            ->where('s.token = :token')
            ->setParameters(['token' => $token])
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
<?php
declare(strict_types=1);

namespace App\User\Infrastructure\ORMIntegration\Repository;

use App\SharedKernel\Infrastructure\ORMIntegration\Repository\EntityRepository;
use App\User\Domain\User;
use Doctrine\ORM\Query;
use App\User\Domain\Users as DomainUsers;
use Behat\Transliterator\Transliterator;

class Users extends EntityRepository implements DomainUsers
{
    public function add(User $user): void
    {
        $this->persistEntity($user);
    }

    public function findOneByEmail(string $email): ?User
    {
        return $this->getORMRepository(User::class)->findOneBy(['email' => $email]);
    }

    public function getUniqueSlug(User $user): string
    {
        $slug = Transliterator::urlize($user->getFirstName() . '.' . $user->getLastName());

        $results = $this->getORMRepository(User::class)
            ->createQueryBuilder('u')
            ->select('u.slug')
            ->where('u.slug LIKE :slug')
            ->setParameter('slug', $slug . '%')
            ->getQuery()
            ->getResult(Query::HYDRATE_SCALAR)
        ;



        return $slug . (count($results) === 0 ? '' : (string) count($results));

    }
}
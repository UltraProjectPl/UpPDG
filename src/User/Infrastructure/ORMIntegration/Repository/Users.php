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
        $slug = Transliterator::urlize($user->getFirstName() . $user->getLastName());

        $results = $this->getORMRepository(User::class)
            ->createQueryBuilder('u')
            ->select('u.slug')
            ->where('REGEX(u.slug, :slug) = 1')
            ->setParameter('slug', '^' . preg_quote($slug, '#') . '\d+$')
            ->getQuery()
            ->getResult(Query::HYDRATE_SCALAR)
        ;

        $userToSlugSuffixMapper = fn (string $currentSlug): int => (int) mb_strlen($currentSlug, mb_strlen($slug));
        $maxSlugSuffixReducer = fn (?int $max, int $current): ?int => max($max, $current);


        $maxSuffix = array_reduce(
            array_map($userToSlugSuffixMapper, $results),
            $maxSlugSuffixReducer,
            null,
        );

        return sprintf('%s%d', $slug, $maxSuffix + 1);
    }
}
<?php

declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\ORMIntegration\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityRepository as ORMEntityRepository;
use Exception;

abstract class EntityRepository
{
    public function __construct(private ManagerRegistry $registry)
    {
    }

    protected function persistEntity(object $entity): void
    {
        $this->getManagerForClass($entity)->persist($entity);
        $this->getManagerForClass($entity)->flush();
    }

    protected function removeEntity(object $entity): void
    {
        $this->getManagerForClass($entity)->remove($entity);
    }

    /** @param class-string<mixed> $class */
    protected function getORMRepository(string $class): ORMEntityRepository
    {
        $repository = $this->registry->getRepository($class);

        if ($repository instanceof ORMEntityRepository) {
            return $repository;
        }

        throw new Exception("Class repository was expected to be instanceof of ORMEntityRepository but is not.");
    }

    protected function getManagerForClass(object|string $class): EntityManagerInterface
    {
        $entityManager = $this->registry->getManagerForClass(
            true === is_object($class) ? get_class($class) : $class
        );
        if ($entityManager instanceof EntityManagerInterface) {
            return $entityManager;
        }

        throw new Exception("Class entityManager was expected to be instanceof of EntityManagerInterface but is not.");
    }
}

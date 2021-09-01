<?php
declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\SymfonyIntegration\DataFixtures;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor as DoctrineORMExecutor;
use Doctrine\Common\DataFixtures\FixtureInterface;

final class ORMExecutor extends DoctrineORMExecutor
{
    /** @param FixtureInterface[] $fixtures */
    public function execute(array $fixtures, $append = false): void
    {
        $this->purge();

        foreach ($fixtures as $fixture) {
            $this->load($this->getObjectManager(), $fixture);
        }
    }
}
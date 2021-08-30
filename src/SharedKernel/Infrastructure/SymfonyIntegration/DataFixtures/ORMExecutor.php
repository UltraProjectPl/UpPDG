<?php
declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\SymfonyIntegration\DataFixtures;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor as DoctrineORMExecutor;

final class ORMExecutor extends DoctrineORMExecutor
{
    public function execute(array $fixtures, $append = false): void
    {
        $this->purge();

        foreach ($fixtures as $fixture) {
            $this->load($this->getObjectManager(), $fixture);
        }
    }
}
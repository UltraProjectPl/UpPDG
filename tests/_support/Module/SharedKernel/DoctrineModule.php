<?php
declare(strict_types=1);

namespace App\Tests\Module\SharedKernel;

use Codeception\Module;
use Codeception\TestInterface;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

class DoctrineModule extends Module
{
    public function _before(TestInterface $test): void
    {
        $em = $this->getModule(SymfonyModule::class)->grabService('doctrine.orm.default_entity_manager');
        $purger = new ORMPurger($em);

        $purger->purge();
    }
}
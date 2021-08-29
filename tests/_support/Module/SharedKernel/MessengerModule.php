<?php
declare(strict_types=1);

namespace App\Tests\Module\SharedKernel;

use Codeception\Lib\ModuleContainer;
use Codeception\Module;
use Codeception\TestInterface;
use Doctrine\ORM\EntityManagerInterface;

class MessengerModule extends Module
{
    private SymfonyModule $symfonyModule;

    private ContainerModule $containerModule;

    private EntityManagerInterface $entityManager;

    public function __construct(ModuleContainer $moduleContainer, $config = null)
    {
        parent::__construct($moduleContainer, $config);

        $this->symfonyModule = $this->getModule(SymfonyModule::class);
        $this->containerModule = $this->getModule(ContainerModule::class);
    }

    public function _before(TestInterface $test)
    {
        $this->entityManager = $this->symfonyModule->grabService('doctrine.orm.entity_manager');
    }

    public function consumeDispatchedCommands(array $objectsToRefresh = []): void
    {
        foreach ($objectsToRefresh as $object) {
            $this->entityManager->refresh($object);
        }
    }
}
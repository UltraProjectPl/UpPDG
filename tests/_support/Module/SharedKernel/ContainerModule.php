<?php

declare(strict_types=1);

namespace App\Tests\Module\SharedKernel;

use Codeception\Lib\ModuleContainer;
use Codeception\Module;

class ContainerModule extends Module
{
    private SymfonyModule $symfonyModule;

    public function __construct(ModuleContainer $moduleContainer, $config = null)
    {
        parent::__construct($moduleContainer, $config);
        $this->symfonyModule = $moduleContainer->getModule(SymfonyModule::class);
    }

    public function grabTestService(string $id): object
    {
        return $this->grabService("test.{$id}");
    }

    public function grabService(string $id): object
    {
        return $this->symfonyModule->grabService($id);
    }

    public function getParameter(string $id)
    {
        return $this->symfonyModule->_getContainer()->getParameter($id);
    }
}

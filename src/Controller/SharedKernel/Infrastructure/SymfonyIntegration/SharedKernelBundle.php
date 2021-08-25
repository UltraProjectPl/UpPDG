<?php
declare(strict_types=1);

namespace App\Controller\SharedKernel\Infrastructure\SymfonyIntegration;

use App\Controller\SharedKernel\Infrastructure\SymfonyIntegration\DependencyInjection\CompilerPass\FormMapCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SharedKernelBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new FormMapCompilerPass());
    }
}
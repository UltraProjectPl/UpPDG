<?php

declare(strict_types=1);

namespace App\Employer\Infrastructure\SymfonyIntegration\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class EmployerExtension extends Extension
{
    /** @param array<string, string> $configs */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $loader->load('services.xml');
        $loader->load('doctrine.xml');
    }
}

<?php
declare(strict_types=1);

namespace App\User\Infrastructure\SymfonyIntegration\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

final class UserExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new XmlFileLoader(__DIR__ . '/../Resources/config');

        $loader->load('services.xml');
        $loader->load('doctrine.xml');
    }
}
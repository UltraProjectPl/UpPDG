<?php
declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\SymfonyIntegration\DependencyInjection;

use App\SharedKernel\Application\Bus\CommandHandlerInterface;
use App\SharedKernel\Application\Bus\EventSubscriberInterface;
use App\SharedKernel\Application\Bus\NotificationHandlerInterface;
use App\SharedKernel\Application\Bus\QueryHandlerInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class SharedKernelExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $container->registerForAutoconfiguration(CommandHandlerInterface::class)->addTag('messenger.message_handler');
        $container->registerForAutoconfiguration(QueryHandlerInterface::class)->addTag('messenger.message_handler');
        $container->registerForAutoconfiguration(NotificationHandlerInterface::class)->addTag('messenger.message_handler');
        $container->registerForAutoconfiguration(EventSubscriberInterface::class)->addTag('messenger.message_handler');

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $loader->load('services.xml');
        $loader->load('buses.xml');;
    }
}
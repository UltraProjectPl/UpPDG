<?php
declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\SymfonyIntegration\DependencyInjection\CompilerPass;

use App\SharedKernel\Application\Form\FormInterface;
use App\SharedKernel\Infrastructure\SymfonyIntegration\Form\FormClassResolver;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use InvalidArgumentException;

class FormMapCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $forms = array_reduce(
            array_keys($container->findTaggedServiceIds('form.type')),
            static function (array $accumulator, string $id) use ($container): array {
                $definition = $container->getDefinition($id);
                if (null === $definition->getClass()) {
                    throw new InvalidArgumentException(sprintf(
                        'Definition "%s" does not have class',
                        $id
                    ));
                }

                if (true === is_subclass_of($definition->getClass(), FormInterface::class, true)) {
                    $accumulator[] = $definition;
                }

                return $accumulator;
            },
            [],
        );

        $container->getDefinition(FormClassResolver::class)->setArgument('$forms', $forms);
    }
}
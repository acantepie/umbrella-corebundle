<?php

namespace Umbrella\CoreBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;
use Umbrella\CoreBundle\Component\Menu\MenuProvider;
use Umbrella\CoreBundle\Component\Menu\MenuRendererProvider;
use Umbrella\CoreBundle\Component\Menu\Renderer\MenuRendererInterface;

/**
 * Class MenuPass
 */
class MenuPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition(MenuProvider::class)) {
            $definition = $container->getDefinition(MenuProvider::class);

            foreach ($container->findTaggedServiceIds('umbrella.menu.factory') as $id => $tags) {
                foreach ($tags as $attributes) {

                    if (empty($attributes['alias'])) {
                        throw new \InvalidArgumentException(sprintf('The alias is not defined in the "umbrella.menu.factory" tag for the service "%s"', $id));
                    }

                    if (empty($attributes['method'])) {
                        throw new \InvalidArgumentException(sprintf('The method is not defined in the "umbrella.menu.factory" tag for the service "%s"', $id));
                    }

                    $definition->addMethodCall('register', [$attributes['alias'], new Reference($id), $attributes['method']]);
                }
            }

        }

        if ($container->hasDefinition(MenuRendererProvider::class)) {
            $definition = $container->getDefinition(MenuRendererProvider::class);

            foreach ($container->findTaggedServiceIds('umbrella.menu_renderer') as $id => $tags) {
                $rendererDefinition = $container->findDefinition($id);

                if (!is_subclass_of($rendererDefinition->getClass(), MenuRendererInterface::class)) {
                    throw new \InvalidArgumentException(sprintf('Service "%s" with tag "umbrella.menu_renderer" must implements interface "%s"', $id, MenuRendererInterface::class));
                }

                foreach ($tags as $attributes) {

                    if (empty($attributes['alias'])) {
                        throw new \InvalidArgumentException(sprintf('The alias is not defined in the "umbrella.menu_renderer" tag for the service "%s"', $id));
                    }

                    $definition->addMethodCall('register', [$attributes['alias'], new Reference($id)]);
                }
            }
        }

    }
}

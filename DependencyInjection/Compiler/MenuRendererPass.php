<?php

namespace Umbrella\CoreBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Umbrella\CoreBundle\Component\Menu\MenuRendererProvider;

/**
 * Class MenuRendererPass.
 */
class MenuRendererPass implements CompilerPassInterface
{
    const TAG = 'umbrella.menu_renderer';

    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition(MenuRendererProvider::class);

        foreach ($container->findTaggedServiceIds(self::TAG) as $id => $tags) {
            $rendererDefinition = $container->getDefinition($id);

            if (!$rendererDefinition->isPublic()) {
                throw new \InvalidArgumentException(sprintf('Menu renderer services must be public but "%s" is a private service.', $id));
            }

            if ($rendererDefinition->isAbstract()) {
                throw new \InvalidArgumentException(sprintf('Abstract services cannot be registered as menu builders but "%s" is.', $id));
            }

            foreach ($tags as $attributes) {
                if (empty($attributes['alias'])) {
                    throw new \InvalidArgumentException(sprintf('The alias is not defined in the "%s" tag for the service "%s"', self::TAG, $id));
                }
                $definition->addMethodCall('register', [$attributes['alias'], $id]);
            }
        }
    }
}

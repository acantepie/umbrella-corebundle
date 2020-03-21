<?php

namespace Umbrella\CoreBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Umbrella\CoreBundle\Component\Menu\MenuProvider;

/**
 * Class MenuBuilderPass.
 */
class MenuPass implements CompilerPassInterface
{
    const TAG = 'umbrella.menu';

    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition(MenuProvider::class);

        foreach ($container->findTaggedServiceIds(self::TAG) as $id => $tags) {
            $builderDefinition = $container->getDefinition($id);

            if (!$builderDefinition->isPublic()) {
                throw new \InvalidArgumentException(sprintf('Menu services must be public but "%s" is a private service.', $id));
            }

            if ($builderDefinition->isAbstract()) {
                throw new \InvalidArgumentException(sprintf('Abstract services cannot be registered as menu builders but "%s" is.', $id));
            }

            foreach ($tags as $attributes) {

                if (empty($attributes['alias'])) {
                    throw new \InvalidArgumentException(sprintf('The alias is not defined in the "%s" tag for the service "%s"', self::TAG, $id));
                }
                if (empty($attributes['method'])) {
                    throw new \InvalidArgumentException(sprintf('The method is not defined in the "%s" tag for the service "%s"', self::TAG, $id));
                }

                $definition->addMethodCall('register', [$attributes['alias'], $id, $attributes['method']]);
            }
        }
    }
}

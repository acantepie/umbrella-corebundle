<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 16/03/18
 * Time: 14:48
 */

namespace Umbrella\CoreBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class DataTablePass
 */
class UmbrellaComponentPass implements CompilerPassInterface
{

    /**
     * You can modify the container here before it is dumped to PHP code.
     */
    public function process(ContainerBuilder $container)
    {
        $mustBePublic =
            $container->findTaggedServiceIds('umbrella.datatable.type') +
            $container->findTaggedServiceIds('umbrella.datatable.query') +
            $container->findTaggedServiceIds('umbrella.toolbar.type') +
            $container->findTaggedServiceIds('umbrella.tree.type') +
            $container->findTaggedServiceIds('umbrella.column.type') +
            $container->findTaggedServiceIds('umbrella.action.type');

        foreach ($mustBePublic as $serviceId => $tag) {
            $serviceDefinition = $container->getDefinition($serviceId);
            if (!$serviceDefinition->isPublic()) {
                $serviceDefinition->setPublic(true);
            }
        }
    }
}
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
use Symfony\Component\DependencyInjection\Reference;
use Umbrella\CoreBundle\Component\DataTable\ColumnFactory;
use Umbrella\CoreBundle\Component\DataTable\DataTableFactory;
use Umbrella\CoreBundle\Component\Toolbar\ActionFactory;
use Umbrella\CoreBundle\Component\Toolbar\ToolbarFactory;

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
        $this->storeTaggedServiceToRegistry($container, ColumnFactory::class, 'umbrella.column.type', 'registerColumnType');
        $this->storeTaggedServiceToRegistry($container, DataTableFactory::class, 'umbrella.datatable.type', 'registerDataTableType');
        $this->storeTaggedServiceToRegistry($container, ToolbarFactory::class, 'umbrella.toolbar.type', 'registerToolbarType');
        $this->storeTaggedServiceToRegistry($container, ActionFactory::class, 'umbrella.action.type', 'registerActionType');
    }

    private function storeTaggedServiceToRegistry(ContainerBuilder $container, $registryClass, $tag, $method = 'registerType')
    {
        // always first check if the primary service is defined
        if (!$container->has($registryClass)) {
            return;
        }

        $definition = $container->findDefinition($registryClass);
        $taggedServices = $container->findTaggedServiceIds($tag);

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall($method, [$id, new Reference($id)]);
        }
    }
}
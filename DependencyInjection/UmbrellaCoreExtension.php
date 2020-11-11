<?php

namespace Umbrella\CoreBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Umbrella\CoreBundle\Component\Action\Type\ActionType;
use Umbrella\CoreBundle\Component\Ckeditor\CkeditorConfiguration;
use Umbrella\CoreBundle\Component\Column\Type\ColumnType;
use Umbrella\CoreBundle\Component\DataTable\Type\DataTableType;
use Umbrella\CoreBundle\Component\Schedule\Task\AbstractTask;
use Umbrella\CoreBundle\Extension\WebpackTwigExtension;
use Umbrella\CoreBundle\Services\UmbrellaRedis;
use Umbrella\CoreBundle\Utils\ArrayUtils;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class UmbrellaCoreExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('form_extension.yml');

        $def = $container->getDefinition(WebpackTwigExtension::class);
        $def->addMethodCall('loadConfig', [$config['webpack']]);

        $def = $container->getDefinition(UmbrellaRedis::class);
        $def->addMethodCall('loadConfig', [$config['redis']]);

        $def = $container->getDefinition(CkeditorConfiguration::class);
        $def->setArgument(0, $config['ckeditor']);

        $container->registerForAutoconfiguration(DataTableType::class)->addTag('umbrella.datatable.type');
        $container->registerForAutoconfiguration(ColumnType::class)->addTag('umbrella.column.type');
        $container->registerForAutoconfiguration(ActionType::class)->addTag('umbrella.action.type');
        $container->registerForAutoconfiguration(AbstractTask::class)->addTag('umbrella.task');

        $parameters = ArrayUtils::remap_nested_array($config, 'umbrella_core');

        foreach ($parameters as $pKey => $pValue) {
            if (!$container->hasParameter($pKey)) {
                $container->setParameter($pKey, $pValue);
            }
        }
    }
}

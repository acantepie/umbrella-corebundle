<?php

namespace Umbrella\CoreBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Umbrella\CoreBundle\Component\DataTable\Model\DataTableQueryInterface;
use Umbrella\CoreBundle\Component\DataTable\Type\ColumnType;
use Umbrella\CoreBundle\Component\DataTable\Type\DataTableType;
use Umbrella\CoreBundle\Component\Toolbar\Type\ActionType;
use Umbrella\CoreBundle\Component\Toolbar\Type\ToolbarType;
use Umbrella\CoreBundle\Component\Tree\Type\TreeType;
use Umbrella\CoreBundle\Component\Webpack\Twig\WebpackTwigExtension;
use Umbrella\CoreBundle\Model\UserAwareInterface;
use Umbrella\CoreBundle\Services\UmbrellaFileUploader;
use Umbrella\CoreBundle\Services\UmbrellaMailer;
use Umbrella\CoreBundle\Services\UmbrellaRedis;

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

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        // load form extension only if enable
        if ($config['form']['enable_extension'] === true) {
            $loader->load('form_extension.yml');
        }

        $def = $container->getDefinition(WebpackTwigExtension::class);
        $def->addMethodCall('loadConfig', [$config['webpack']]);

        $def = $container->getDefinition(UmbrellaFileUploader::class);
        $def->addMethodCall('loadConfig', [$config['file']]);

        $def = $container->getDefinition(UmbrellaMailer::class);
        $def->addMethodCall('loadConfig', [$config['mailer']]);

        $def = $container->getDefinition(UmbrellaRedis::class);
        $def->addMethodCall('loadConfig', [$config['redis']]);

        $container->registerForAutoconfiguration(DataTableType::class)->addTag('umbrella.datatable.type');
        $container->registerForAutoconfiguration(DataTableQueryInterface::class)->addTag('umbrella.datatable.query');
        $container->registerForAutoconfiguration(ToolbarType::class)->addTag('umbrella.toolbar.type');
        $container->registerForAutoconfiguration(TreeType::class)->addTag('umbrella.tree.type');
        $container->registerForAutoconfiguration(ColumnType::class)->addTag('umbrella.column.type');
        $container->registerForAutoconfiguration(ActionType::class)->addTag('umbrella.action.type');
    }
}

<?php

namespace Umbrella\CoreBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('umbrella_core');
        $rootNode = $treeBuilder->getRootNode();

        $this->addwebpackSection($rootNode);
        $this->addRedisSection($rootNode);
        $this->ckeditorSection($rootNode);

        return $treeBuilder;
    }

    private function addwebpackSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode->children()
            ->arrayNode('webpack')->addDefaultsIfNotSet()
            ->children()
                ->booleanNode('dev_server_enable')->defaultFalse()->end()
                ->scalarNode('dev_server_host')->defaultValue('http://127.0.0.1')->end()
                ->integerNode('dev_server_port')->defaultValue(9000)->end();
    }

    private function addRedisSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode->children()
            ->arrayNode('redis')->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('host')->defaultValue('127.0.0.1')->end()
                ->scalarNode('port')->defaultValue('6379')->end()
                ->scalarNode('db')->defaultValue('0')->end();
    }

    private function ckeditorSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode->children()
            ->arrayNode('ckeditor')->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('default_config')->defaultNull()->end()
                ->arrayNode('configs')
                    ->useAttributeAsKey('name')
                    ->normalizeKeys(false)
                    ->arrayPrototype()
                        ->variablePrototype()->end()
                    ->end();
    }
}

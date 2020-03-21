<?php

namespace Umbrella\CoreBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

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
        $treeBuilder
            ->getRootNode()
            ->append($this->webpackNode())
            ->append($this->formNode())
            ->append($this->fileNode())
            ->append($this->redisNode());
        return $treeBuilder;
    }

    private function webpackNode()
    {
        $treeBuilder = new TreeBuilder('webpack');
        $webpackNode = $treeBuilder->getRootNode()->addDefaultsIfNotSet();
        $webpackNode->children()
            ->booleanNode('dev_server_enable')
                ->defaultFalse()
                ->end()
            ->scalarNode('dev_server_host')
                ->defaultValue('http://127.0.0.1')
                ->end()
            ->integerNode('dev_server_port')
                ->defaultValue(9000)
                ->end();
        return $webpackNode;
    }

    private function formNode()
    {
        $treeBuilder = new TreeBuilder('form');
        $node = $treeBuilder->getRootNode()->addDefaultsIfNotSet();
        $node->children()
            ->booleanNode('enable_extension')
            ->defaultTrue();

        return $node;
    }

    private function fileNode()
    {
        $treeBuilder = new TreeBuilder('file');
        $node = $treeBuilder->getRootNode()->addDefaultsIfNotSet();
        $node->children()
            ->scalarNode('asset_path')
                ->defaultValue('/uploads')
                ->end()
            ->scalarNode('web_path')
                ->defaultValue('/web')
                ->end();
        return $node;
    }

    private function redisNode()
    {
        $treeBuilder = new TreeBuilder('redis');
        $node = $treeBuilder->getRootNode()->addDefaultsIfNotSet();
        $node->children()
            ->scalarNode('host')
                ->defaultValue('127.0.0.1')
                ->end()
            ->scalarNode('port')
                ->defaultValue('6379')
                ->end()
            ->scalarNode('db')
                ->defaultValue('0')
                ->end();
        return $node;
    }
}
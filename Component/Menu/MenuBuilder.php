<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 13/05/17
 * Time: 16:11.
 */

namespace Umbrella\CoreBundle\Component\Menu;

use Umbrella\CoreBundle\Component\Menu\Model\Menu;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Component\Menu\Model\MenuNode;

/**
 * Class MenuBuilder.
 */
class MenuBuilder
{
    /**
     * @var array
     */
    protected $nodes = [];

    /**
     * @var Menu
     */
    protected $resolvedMenu = null;

    /**
     * @param $id
     * @param array $options
     */
    public function addNode($id, array $options = [])
    {
        $this->nodes[$id] = $options;
    }

    /**
     * @return Menu
     */
    public function getMenu()
    {
        if (null === $this->resolvedMenu) {
            $this->resolvedMenu = new Menu();

            $rootNode = new MenuNode();
            $rootNode->type = MenuNode::TYPE_ROOT;
            $this->resolvedMenu->root = $rootNode;

            foreach ($this->nodes as $id => $nodeOptions) {
                $this->resolveNode($id, $nodeOptions, $rootNode);
            }
        }

        return $this->resolvedMenu;
    }

    /**
     * @param $id
     * @param array    $options
     * @param MenuNode $parentNode
     */
    private function resolveNode($id, array $options, MenuNode $parentNode)
    {
        $options['id'] = $id;

        $node = new MenuNode();

        $resolver = new OptionsResolver();
        $node->configureOptions($resolver);
        $resolvedOptions = $resolver->resolve($options);
        $node->setOptions($resolvedOptions);
        $parentNode->addChild($id, $node);

        foreach ($resolvedOptions['children'] as $id => $childOptions) {
            $this->resolveNode($id, $childOptions, $node);
        }
    }
}

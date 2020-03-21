<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 13/05/17
 * Time: 16:11.
 */

namespace Umbrella\CoreBundle\Component\Menu;

use Symfony\Component\Routing\RouterInterface;
use Umbrella\CoreBundle\Component\Menu\Model\Menu;
use Umbrella\CoreBundle\Component\Menu\Model\MenuNode;

/**
 * Class MenuBuilder.
 */
class MenuBuilder
{
    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var Menu
     */
    protected $menu;

    /**
     * MenuBuilder constructor.
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
        $this->menu = new Menu();
    }

    /**
     * @return MenuNode
     */
    public function createRootNode()
    {
        $node = new MenuNode();
        $node->type = MenuNode::TYPE_ROOT;

        $this->menu->root = $node;
        return $node;
    }

    /**
     * @param array $options
     *
     * @return MenuNode
     */
    public function createHeaderNode(array $options = array())
    {
        $node = new MenuNode();
        $node->type = MenuNode::TYPE_HEADER;

        if (isset($options['label'])) {
            $node->label = $options['label'];
        }

        if (isset($options['security'])) {
            $node->securityExpression = $options['security'];
        }

        return $node;
    }

    /**
     * @param array $options
     *
     * @return MenuNode
     */
    public function createPageNode(array $options = array())
    {
        $node = new MenuNode();
        $node->type = MenuNode::TYPE_PAGE;

        if (isset($options['label'])) {
            $node->label = $options['label'];
        }

        if (isset($options['translate'])) {
            $node->translate = $options['translate'];
        }

        if (isset($options['icon'])) {
            $node->icon = $options['icon'];
        }

        if (isset($options['security'])) {
            $node->securityExpression = $options['security'];
        }

        if (isset($options['action'])) {
            $action = $options['action'];

            if (is_array($action)) {
                if (isset($action['target'])) {
                    $node->target = $action['target'];
                }

                if (isset($action['url'])) {
                    $node->url = $action['url'];
                }

                if (isset($action['route'])) {
                    $node->route = $action['route'];
                    $node->routeParams = (isset($action['params']) && is_array($action['params'])) ? $action['params'] : array();
                    $node->url = $this->router->generate($node->route, $node->routeParams);
                }
            } else {
                $node->route = $action;
                $node->url = $this->router->generate($node->route);
            }
        }

        return $node;
    }

    /**
     * @return Menu
     */
    public function getMenu()
    {
       return $this->menu;
    }
}

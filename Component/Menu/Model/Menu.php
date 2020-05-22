<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 30/05/17
 * Time: 19:34
 */

namespace Umbrella\CoreBundle\Component\Menu\Model;

/**
 * Class Menu
 * @package Umbrella\CoreBundle\Component\Menu\Model
 */
class Menu implements \IteratorAggregate, \Countable
{
    /**
     * @var MenuNode
     */
    public $root;

    /**
     * @var string
     */
    public $translationPrefix = 'menu.';

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->root->children);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->root->children);
    }

    /**
     * Path of node: e.g: header_admin.user
     * @param $path
     * @return MenuNode
     */
    public function findByPath($path)
    {
        $names = explode('.', $path);

        $current = $this->root;
        foreach ($names as $name) {
            if (!array_key_exists($name, $current->children)) {
                break;
            }

            $current = $current->children[$name];
        }
        return $current;
    }

    /**
     * @param $route
     * @return MenuNode
     */
    public function findByRoute($route)
    {
        $found = $this->_find($this->root, function (MenuNode $node) use ($route) {
            return $node->route == $route;
        });
        return $found ? $found : $this->root;
    }

    /**
     * @param  MenuNode $node
     * @param  callable $finder
     * @return MenuNode
     */
    private function _find(MenuNode $node, callable $finder)
    {
        if ($finder($node)) {
            return $node;
        }

        foreach ($node->children as $name => $child) {
            if (null !== ($found = $this->_find($child, $finder))) {
                return $found;
            }
        }
        return null;
    }
}

<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 13/05/17
 * Time: 16:05.
 */

namespace Umbrella\CoreBundle\Component\Menu\Model;

/**
 * Class MenuNode.
 */
class MenuNode implements \IteratorAggregate, \Countable
{
    const TYPE_ROOT = 'ROOT';
    const TYPE_HEADER = 'HEADER';
    const TYPE_PAGE = 'PAGE';

    const DFT_URL = '#';
    const DFT_TARGET = '_self';

    /**
     * @var string
     */
    public $type;

    /**
     * @var MenuNode
     */
    public $parent;

    /**
     * @var array
     */
    public $children = array();

    /**
     * @var string
     */
    public $icon;

    /**
     * @var string
     */
    public $label;

    /**
     * @var bool
     */
    public $translate = true;

    /**
     * @var string
     */
    public $url = self::DFT_URL;

    /**
     * @var string
     */
    public $target = self::DFT_TARGET;

    /**
     * @var string
     */
    public $securityExpression = null;

    /**
     * whether the item is current. null means unknown
     * @var boolean|null
     */
    public $isCurrent = null;

    /* Keep route and routeParams options for url matcher */

    /**
     * @var string
     */
    public $route;

    /**
     * @var array
     */
    public $routeParams = array();

    /**
     * @param bool $current
     */
    public function setCurrent($current = true)
    {
        $this->isCurrent = $current;
    }

    /**
     * @return int|mixed
     */
    public function getLevel()
    {
        return $this->parent ? $this->parent->getLevel() + 1 : 0;
    }

    /**
     * @param $name
     * @param MenuNode $child
     * @return $this
     */
    public function addChild($name, MenuNode $child)
    {
        if (array_key_exists($name, $this->children)) {
            throw new \InvalidArgumentException('Cannot use this node name, name is already used by sibling.');
        }

        $child->parent = $this;
        $this->children[$name] = $child;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasChildren()
    {
        return count($this->children) > 0;
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->children);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->children);
    }
}

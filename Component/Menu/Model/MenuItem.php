<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 13/05/17
 * Time: 16:05.
 */

namespace Umbrella\CoreBundle\Component\Menu\Model;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Component\Menu\MenuFactory;

/**
 * Class MenuItem
 */
class MenuItem implements \Countable, \IteratorAggregate
{
    const ID_REGEXP = '/^[0a-zA-Z0-9\-\_\.]+$/';

    const TYPE_TITLE = 'title';
    const TYPE_ITEM = 'item';


    /**
     * @var MenuItem
     */
    protected $parent;

    /**
     * Children map using id as key
     * @var MenuItem[]
     */
    protected $children = [];

    /**
     * @var MenuFactory
     */
    protected $factory;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $icon;

    /**
     * @var string
     */
    public $label;

    /**
     * @var string|null
     */
    public $translationDomain;

    /**
     * @var string
     */
    public $route;

    /**
     * @var array
     */
    public $routeParams = [];

    /**
     * @var string
     */
    public $security;

    /**
     * whether the item is current. null means unknown
     * @var boolean|null
     */
    public $isCurrent = null;

    /**
     * MenuItem constructor.
     * @param $id
     * @param MenuFactory $factory
     */
    public function __construct($id, MenuFactory $factory)
    {
        if (!preg_match(self::ID_REGEXP, $id)) {
            throw new \RuntimeException(sprintf('MenuItem id "%s" is invalid', $id));
        }

        $this->id = $id;
        $this->factory = $factory;
    }

    /**
     * @return bool
     */
    public function isRoot()
    {
        return $this->parent === null;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->isRoot() ? '' : sprintf('%s:%s', $this->parent->getPath(), $this->id);
    }

    /**
     * @param $pattern
     * @param false $regexp
     * @return bool
     */
    public function matchPath($pattern, $regexp = false)
    {
        return $regexp
            ? 0 < preg_match($pattern, $this->getPath())
            : $this->getPath() === $pattern;
    }

    /**
     * @param $route
     * @return bool
     */
    public function mathRoute($route)
    {
        return $this->route === $route;
    }

    /**
     * @return MenuItem
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param MenuItem|null $parent
     * @return $this
     */
    public function setParent(MenuItem $parent = null)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @param bool $current
     * @return $this
     */
    public function setCurrent($current = true)
    {
        $this->isCurrent = $current;
        return $this;
    }

    /**
     * @return int|mixed
     */
    public function getLevel()
    {
        return $this->parent ? $this->parent->getLevel() + 1 : 0;
    }

    /**
     * @param $id
     * @param array $options
     * @return MenuItem
     */
    public function addChild($id, array $options = [])
    {
        $child = $this->factory->_createItem($id, $options);
        $child->setParent($this);
        $this->children[$child->id] = $child;
        return $child;
    }

    /**
     * @return bool
     */
    public function hasChildren()
    {
        return count($this->children) > 0;
    }

    /**
     * @param $id
     * @return bool
     */
    public function hasChild($id)
    {
        return isset($this->children[$id]);
    }

    /**
     * @param $id
     * @return $this
     */
    public function removeChild($id)
    {
        if (isset($this->children[$id])) {
            $this->children[$id]->setParent(null);
            unset($this->children[$id]);
        }
        return $this;
    }

    /**
     * @param $id
     * @return MenuItem|null
     */
    public function getChild($id)
    {
        return isset($this->children[$id]) ? $this->children[$id] : null;
    }


    // Interface implementations

    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->children);
    }

    /**
     * @return \ArrayIterator|MenuItem[]
     */
    public function getFlatIterator()
    {
        $it = new \ArrayIterator();
        foreach ($this->children as $child) {
            $it->append($child);
            foreach ($child->getFlatIterator() as $value)
                $it->append($value);
        }

        return $it;
    }

    /**
     * @inheritDoc
     */
    public function count()
    {
        return count($this->children);
    }
}

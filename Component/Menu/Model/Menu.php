<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 30/05/17
 * Time: 19:34
 */

namespace Umbrella\CoreBundle\Component\Menu\Model;

use Umbrella\CoreBundle\Component\Menu\MenuFactory;

/**
 * Class Menu
 * @package Umbrella\CoreBundle\Component\Menu\Model
 */
class Menu
{
    /**
     * @var MenuItem
     */
    protected $root;

    /**
     * @var array
     */
    private $pathEntries = [];

    /**
     * Menu constructor.
     * @param MenuItem $root
     */
    public function __construct(MenuFactory $factory)
    {
        $this->root = new MenuItem('root', $factory);
    }

    /**
     * @return MenuItem
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * Strict = true => Return a MenuItem with a path equals to $path
     * Strict = false => Return first MenuItem with a path that contains $path
     *
     * @param $path
     * @param false $strict
     *
     * @return MenuItem|null
     */
    public function search($path, $strict = false)
    {
        foreach ($this->root->getFlatIterator() as $item) {
            if ($item->matchPath($path, $strict)) {
                return $item;
            }
        }
        return null;
    }

    /**
     * @param $route
     * @return MenuItem|null
     */
    public function searchByRoute($route)
    {
        foreach ($this->root->getFlatIterator() as $item) {
            if ($item->mathRoute($route)) {
                return $item;
            }
        }
        return null;
    }

    /**
     * @param $path
     * @param false $strict
     * @param false $quiet
     */
    public function setCurrent($path, $strict = false, $quiet = false)
    {
        $item = $this->search($path, $strict);
        if (null !== $item) {
            $item->setCurrent(true);
        } elseif(!$quiet) {
            throw new \RuntimeException(sprintf('No item found on menu for path "%s" (strict search ? %s)', $path, $strict ? 'true' : 'false'));
        }
    }

    /**
     * @param $route
     * @param false $quiet
     */
    public function setCurrentByRoute($route, $quiet = false)
    {
        $item = $this->searchByRoute($route);
        if (null !== $item) {
            $item->setCurrent(true);
        } elseif(!$quiet) {
            throw new \RuntimeException(sprintf('No item found on menu for route "%s"', $route));
        }
    }

}

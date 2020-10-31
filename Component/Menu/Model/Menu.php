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
     * @param $pattern
     * @param bool $regexp
     *
     * @return null|MenuItem
     */
    public function search($pattern, $regexp = false)
    {
        foreach ($this->root->getFlatIterator() as $item) {
            if ($item->matchPath($pattern, $regexp)) {
                return $item;
            }
        }
        return null;
    }

    /**
     * @param $pattern
     * @param bool $regexp
     * @param false $quiet
     */
    public function setCurrent($pattern, $regexp = false, $quiet = false)
    {
        $item = $this->search($pattern, $regexp);
        if (null !== $item) {
            $item->setCurrent(true);
        } elseif(!$quiet) {
            throw new \RuntimeException(sprintf('No item found on menu for pattern %s(%s)', $regexp ? 'r' : 's', $pattern));
        }
    }

}

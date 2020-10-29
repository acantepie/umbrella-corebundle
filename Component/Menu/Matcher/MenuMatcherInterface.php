<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 30/11/18
 * Time: 14:05
 */

namespace Umbrella\CoreBundle\Component\Menu\Matcher;

use Umbrella\CoreBundle\Component\Menu\Model\MenuItem;

/**
 * Interface MenuMatcherInterface
 * @package Umbrella\CoreBundle\Component\Matcher\Menu
 */
interface MenuMatcherInterface
{
    /**
     * Checks whether an item is current.
     *
     * @param  MenuItem $item
     * @return bool
     */
    public function isCurrent(MenuItem $item);

    /**
     * Checks whether an item is the ancestor of a current item.
     *
     * @param  MenuItem $item
     * @return bool
     */
    public function isAncestor(MenuItem $item);
}

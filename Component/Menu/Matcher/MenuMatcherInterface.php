<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 30/11/18
 * Time: 14:05
 */

namespace Umbrella\CoreBundle\Component\Menu\Matcher;

use Umbrella\CoreBundle\Component\Menu\Model\MenuNode;

/**
 * Interface MenuMatcherInterface
 * @package Umbrella\CoreBundle\Component\Matcher\Menu
 */
interface MenuMatcherInterface
{
    /**
     * Checks whether an item is current.
     *
     * @param MenuNode $node
     * @return bool
     */
    public function isCurrent(MenuNode $node);

    /**
     * Checks whether an item is the ancestor of a current item.
     *
     * @param MenuNode $node
     * @return bool
     */
    public function isAncestor(MenuNode $node);

}

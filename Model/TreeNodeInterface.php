<?php

namespace Umbrella\CoreBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Interface TreeInterface
 * @package Umbrella\CoreBundle\Model
 */
interface TreeNodeInterface
{
    /**
     * @return int
     */
    public function getId(): int;

    /**
     * @return int
     */
    public function getLvl(): int;

    /**
     * @return TreeNodeInterface
     */
    public function getParent(): TreeNodeInterface;

    /**
     * @return iterable
     */
    public function getChildren(): ArrayCollection;
}

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
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * @return int|null
     */
    public function getLvl(): ?int;

    /**
     * @return TreeNodeInterface
     */
    public function getParent(): TreeNodeInterface;

    /**
     * @return iterable
     */
    public function getChildren(): ArrayCollection;

    /**
     * @param  TreeNodeInterface $node
     * @return bool
     */
    public function isChildOf(TreeNodeInterface  $node) : bool;
}

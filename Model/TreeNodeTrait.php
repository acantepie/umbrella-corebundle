<?php

namespace Umbrella\CoreBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Trait TreeEntityTrait
 */
trait TreeNodeTrait
{
    use IdTrait;

    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(type="integer")
     */
    public $lft;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(type="integer")
     */
    public $lvl;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(type="integer")
     */
    public $rgt;

    /**
     * {@inheritdoc}
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getLvl(): ?int
    {
        return $this->lvl;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): TreeNodeInterface
    {
        return $this->parent;
    }

    /**
     * {@inheritdoc}
     */
    public function getChildren(): ArrayCollection
    {
        return $this->children;
    }

    /**
     * @param TreeNodeInterface $child
     */
    public function addChild(TreeNodeInterface $child)
    {
        $child->parent = $this;
        $this->children->add($child);
    }

    /**
     * @param TreeNodeInterface $child
     */
    public function removeChild(TreeNodeInterface $child)
    {
        $child->parent = null;
        $this->children->removeElement($child);
    }

    /**
     * @param TreeNodeInterface $node
     */
    public function isChildOf(TreeNodeInterface $node): bool
    {
        if ($this->getLvl() <= $node->getLvl() || null === $this->getParent()) {
            return false;
        }

        if ($this->getParent() === $node) {
            return true;
        }

        return $this->getParent()->isChildOf($node);
    }
}

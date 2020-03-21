<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 03/06/17
 * Time: 10:39
 */
namespace Umbrella\CoreBundle\Component\Tree\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Umbrella\CoreBundle\Component\Core\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class BaseTreeEntity
 *
 * @ORM\MappedSuperclass
 */
abstract class BaseTreeEntity extends BaseEntity implements \Countable, \IteratorAggregate
{

    /**
     * @var int
     * @Gedmo\TreeLeft
     * @ORM\Column(type="integer")
     */
    public $lft;

    /**
     * @var int
     * @Gedmo\TreeRight
     * @ORM\Column(type="integer")
     */
    public $rgt;

    /**
     * @var int
     * @Gedmo\TreeLevel
     * @ORM\Column(type="integer")
     */
    public $lvl;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, unique=true, nullable=true)
     */
    public $rootAlias;

    /**
     * @var BaseTreeEntity
     */
    public $parent;

    /**
     * @var ArrayCollection
     */
    public $children;

    /**
     * BaseTreeEntity constructor.
     */
    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    /**
     * @param BaseTreeEntity $entity
     * @return $this
     */
    public function addChild(BaseTreeEntity $entity)
    {
        $entity->parent = $this;
        $this->children->add($entity);
        return $this;
    }

    /**
     * @param BaseTreeEntity $entity
     * @return $this
     */
    public function removeChild(BaseTreeEntity $entity)
    {
        $entity->parent = null;
        $this->children->removeElement($entity);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getIterator()
    {
        return $this->children->getIterator();
    }

    /**
     * @inheritdoc
     */
    public function count()
    {
        return count($this->children);
    }
}
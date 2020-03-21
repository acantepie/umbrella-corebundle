<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 07/05/17
 * Time: 18:50.
 */

namespace Umbrella\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Umbrella\CoreBundle\Annotation\Searchable;

/**
 * Class BaseEntity.
 *
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 *
 * @Searchable(searchField="search")
 */
abstract class BaseEntity
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @var \DateTime|null
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    public $createdAt;

    /**
     * @var \DateTime|null
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    public $updatedAt;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    public $search;

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->id;
    }

    /**
     * @ORM\PrePersist
     */
    public function _timestampable_prePersist()
    {
        $now = new \DateTime('NOW');

        if (!$this->createdAt){
            $this->createdAt = $now;
        }

        if (!$this->updatedAt) {
            $this->updatedAt = $now;
        }
    }

    /**
     * @ORM\PreUpdate
     */
    public function _timestampable_preUpdate()
    {
        $now = new \DateTime('NOW');
        $this->updatedAt = $now;
    }
}

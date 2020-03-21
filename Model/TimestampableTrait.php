<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 13/04/18
 * Time: 11:07
 */

namespace Umbrella\CoreBundle\Model;

use Doctrine\ORM\Mapping as ORM;

trait TimestampableTrait
{
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
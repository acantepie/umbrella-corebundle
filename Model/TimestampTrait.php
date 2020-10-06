<?php


namespace Umbrella\CoreBundle\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait TimestampTrait
 * @package Umbrella\CoreBundle\Model
 */
trait TimestampTrait
{
    /**
     * @var \DateTimeInterface
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    public $createdAt;

    /**
     * @var \DateTimeInterface
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */
    public $updatedAt;

    /**
     * @ORM\PrePersist()
     */
    public function updateCreatedAt()
    {
        $this->createdAt = new \DateTime('NOW');
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function updateUpdatedAt()
    {
        $this->updatedAt = new \DateTime('NOW');
    }
}
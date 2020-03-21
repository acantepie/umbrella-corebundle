<?php


namespace Umbrella\CoreBundle\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait ArchivableTrait
 */
trait ArchivableTrait
{
    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $archivedAt;

    /**
     * @return bool
     */
    public function isArchived()
    {
        return $this->archivedAt !== null;
    }

    public function archive()
    {
        $this->archivedAt = new \DateTime('NOW');
    }

}

<?php


namespace Umbrella\CoreBundle\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait OrderableTrait
 */
trait OrderableTrait
{
    /**
     * @var int
     * @ORM\Column(type="integer", nullable=false, options={"default": 0})
     */
    public $sequence = 0;
}

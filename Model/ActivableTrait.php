<?php

namespace Umbrella\CoreBundle\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait ActivableTrait
 */
trait ActivableTrait
{
    /**
     * @var bool
     * @ORM\Column(name="active", type="boolean", options={"default": TRUE})
     */
    public $active = true;
}

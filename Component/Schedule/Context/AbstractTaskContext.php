<?php

namespace Umbrella\CoreBundle\Component\Schedule\Context;

use Doctrine\ORM\Mapping as ORM;
use Umbrella\CoreBundle\Model\IdTrait;


/**
 * Class AbstractTaskContext
 *
 * Run Context for a job, must be persisted
 *
 * @ORM\MappedSuperclass
 */
class AbstractTaskContext
{
    use IdTrait;

    /**
     * @return string
     */
    public final function getContextId()
    {
        return sprintf('%s:%s', get_class($this), $this->id);
    }

}
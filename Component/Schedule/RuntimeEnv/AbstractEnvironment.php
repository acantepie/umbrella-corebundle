<?php

namespace Umbrella\CoreBundle\Component\Schedule\RuntimeEnv;

use Doctrine\ORM\Mapping as ORM;
use Umbrella\CoreBundle\Model\IdTrait;


/**
 * Class AbstractEnvironment
 *
 * RunTime env for a job, must be persisted
 *
 * @ORM\MappedSuperclass
 */
class AbstractEnvironment
{
    use IdTrait;

    /**
     * @return string
     */
    public final function getRuntimeEnvId()
    {
        return sprintf('%s:%s', get_class($this), $this->id);
    }

}
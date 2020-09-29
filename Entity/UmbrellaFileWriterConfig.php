<?php

namespace Umbrella\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 */
class UmbrellaFileWriterConfig extends BaseEntity
{
    // Config will be persisted only with schedule mode
    
    const MODE_DIRECT = 'direct';
    const MODE_SCHEDULE = 'schedule';

    /**
     * @var string
     */
    public $mode;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    public $handlerAlias;

    /**
     * @var array
     * @ORM\Column(type="json", nullable=true)
     */
    public $parameters = [];

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    public $outputFilePath;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    public $outputPrettyFilename;
}

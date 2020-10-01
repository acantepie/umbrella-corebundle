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
     * Only schedule mode if persisted
     * @var string
     */
    public $mode;

    /**
     * Stored on task if persisted
     * @var string
     */
    public $label;

    /**
     * Only configurable for schedule mode - stored on task
     * @var bool
     */
    public $displayAsNotification = false;

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

    /**
     * UmbrellaFileWriterConfig constructor.
     * @param string $handlerAlias
     */
    public function __construct(string $handlerAlias)
    {
        $this->handlerAlias = $handlerAlias;
    }
}

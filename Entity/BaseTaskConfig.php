<?php

namespace Umbrella\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Umbrella\CoreBundle\Model\IdTrait;
use Umbrella\CoreBundle\Model\TimestampTrait;

/**
 * Class BaseTaskConfig
 *
 * @ORM\Entity()
 * @ORM\Table("umbrella_task_config")
 * @ORM\HasLifecycleCallbacks()
 *
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 *
 */
abstract class BaseTaskConfig
{
    use IdTrait;
    use TimestampTrait;

    /**
     * Process timeout (s)
     *
     * @var int
     * @ORM\Column(type="smallint", nullable=false)
     */
    public $timeout = 0;

    /**
     * Tag to identify task on database
     *
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    public $tag;

    /**
     * The service id of task handler
     *
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    public $handlerAlias;

    /**
     * Runs of task using this config
     *
     * @var Task[]|arraycollection
     * @ORM\OneToMany(targetEntity="Umbrella\CoreBundle\Entity\Task", mappedBy="config", orphanRemoval=true)
     */
    public $tasks;

    /**
     * BaseTaskConfig constructor.
     * @param $handlerAlias
     */
    public function __construct($handlerAlias)
    {
        $this->handlerAlias = $handlerAlias;
        $this->tasks = new arraycollection();
    }

    /**
     * @param Task $task
     */
    public function addTask(Task $task)
    {
        $task->config = $this;
        $this->tasks->add($task);
    }

    /**
     * @param Task $task
     */
    public function removeTask(Task $task)
    {
        $task->config = null;
        $this->tasks->removeElement($task);
    }
}

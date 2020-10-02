<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 19/01/19
 * Time: 15:41
 */

namespace Umbrella\CoreBundle\Component\Task\Pool;

use Umbrella\CoreBundle\Entity\Task;
use Symfony\Component\Process\Process;

/**
 * Class PoolItem
 */
class PoolItem
{
    /**
     * @var Task
     */
    public $task;

    /**
     * @var Process
     */
    public $process;

    /**
     * @var int
     */
    public $stdOutputCursor = 0;

    /**
     * @var int
     */
    public $errorOutputCursor = 0;

    /**
     * PoolItem constructor.
     * @param Task    $task
     * @param Process $process
     */
    public function __construct(Task $task, Process $process)
    {
        $this->task = $task;
        $this->process = $process;
    }

    /**
     * @param $incr
     */
    public function incrStdOutputCursor($incr)
    {
        $this->stdOutputCursor += $incr;
    }

    /**
     * @param $incr
     */
    public function incrErrorOutputCursor($incr)
    {
        $this->errorOutputCursor += $incr;
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 19/01/19
 * Time: 15:41
 */

namespace Umbrella\CoreBundle\Component\Schedule\Runner;

use Symfony\Component\Process\Process;
use Umbrella\CoreBundle\Entity\Job;

/**
 * Class PoolItem
 */
class PoolItem
{
    /**
     * @var Job
     */
    public $job;

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
     *
     * @param Job     $job
     * @param Process $process
     */
    public function __construct(Job $job, Process $process)
    {
        $this->job = $job;
        $this->process = $process;
    }

    /**
     * Update job output from process
     */
    public function updateJobOutput()
    {
        if (!$this->job->disableOutput) {
            $newStdOutput = substr($this->process->getOutput(), $this->stdOutputCursor);
            $this->stdOutputCursor += strlen($newStdOutput);
            $this->job->addStdOutput($newStdOutput);

            $newErrorOutput = substr($this->process->getErrorOutput(), $this->errorOutputCursor);
            $this->errorOutputCursor += strlen($newErrorOutput);
            $this->job->addErrorOutput($newErrorOutput);
        }
    }
}

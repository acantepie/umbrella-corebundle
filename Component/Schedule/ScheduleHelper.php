<?php


namespace Umbrella\CoreBundle\Component\Schedule;


use Umbrella\CoreBundle\Component\Schedule\Runner\Runner;
use Umbrella\CoreBundle\Component\Schedule\Context\AbstractTaskContext;
use Umbrella\CoreBundle\Component\Schedule\Context\TaskContextProvider;

/**
 * Class ScheduleHelper
 */
class ScheduleHelper
{
    /**
     * @var Scheduler
     */
    private $scheduler;

    /**
     * @var Runner
     */
    private $runner;

    /**
     * @var TaskContextProvider
     */
    private $contextProvider;

    /**
     * ScheduleHelper constructor.
     * @param Scheduler $scheduler
     * @param Runner $runner
     * @param TaskContextProvider $contextProvider
     */
    public function __construct(Scheduler $scheduler, Runner $runner, TaskContextProvider $contextProvider)
    {
        $this->scheduler = $scheduler;
        $this->runner = $runner;
        $this->contextProvider = $contextProvider;
    }

    /**
     * @return Schedule
     */
    public function schedule()
    {
        return $this->scheduler->create();
    }

    /**
     * @param array $jobIds
     */
    public function run($jobIds = [])
    {
        return $this->runner->run($jobIds);
    }

    /**
     * @param $jobId
     * @return AbstractTaskContext
     */
    public function getContext($jobId)
    {
        return $this->contextProvider->getContextOfJob($jobId);
    }


}
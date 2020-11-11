<?php

namespace Umbrella\CoreBundle\Component\Schedule;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Process\PhpExecutableFinder;
use Umbrella\CoreBundle\Component\Schedule\Command\TaskRunCommand;
use Umbrella\CoreBundle\Component\Schedule\RuntimeEnv\AbstractEnvironment;
use Umbrella\CoreBundle\Component\Schedule\Task\TaskFactory;
use Umbrella\CoreBundle\Entity\ArrayRuntimeEnvironment;
use Umbrella\CoreBundle\Entity\Job;

/**
 * Class Scheduler
 */
class Scheduler
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var TaskFactory
     */
    private $taskFactory;

    /**
     * @var string
     */
    private $consolePath;

    /**
     * Scheduler constructor.
     * @param EntityManagerInterface $em
     * @param TaskFactory $taskFactory
     * @param string $consolePath
     */
    public function __construct(EntityManagerInterface $em, TaskFactory $taskFactory, string $consolePath)
    {
        $this->em = $em;
        $this->taskFactory = $taskFactory;
        $this->consolePath = $consolePath;
    }

    /**
     * @return Schedule
     */
    public function create()
    {
        $schedule = new Schedule($this);
        return $schedule;
    }

    /**
     * @param Schedule $schedule
     */
    public function registerSchedule(Schedule $schedule)
    {
        $this->validateSchedule($schedule);

        $job = new Job();
        $job->state = Job::STATE_PENDING;

        $job->processArgs = $this->buildProcessArgs($schedule);
        $job->description = $schedule->getDescription();
        $job->timeout = $schedule->getTimeout();
        $job->disableOutput = $schedule->isDisableOutput();

        $this->em->persist($job);
        $this->em->flush();
        return $job->id;
    }

    public function validateSchedule(Schedule $schedule)
    {
        // validate taskId
        $taskId = $schedule->getTaskId();
        if (null !== $taskId && !$this->taskFactory->has($taskId)) {
            throw new \RuntimeException(sprintf('No task register with id %s, tasks registered are : ', $taskId, implode(', ', $this->taskFactory->listIds())));
        }

        $env = $schedule->getRunTimeEnv();
        if (null !== $env && (!is_array($env) && !is_a($env, AbstractEnvironment::class))) {
            throw new \InvalidArgumentException(sprintf('Runtime env should be an "array" or an "%s" object', AbstractEnvironment::class));
        }

        if (0 === count($schedule->getShellCommand()) && null === $schedule->getTaskId()) {
            throw new \RuntimeException('Schedule isn\'t well configured, no task or shell command setted.');
        }
    }

    private function buildProcessArgs(Schedule $schedule)
    {
        // build command around a task
        if ($schedule->getTaskId() !== null) {

            $args = [];
            $args[] = (new PhpExecutableFinder())->find();
            $args[] = $this->consolePath;
            $args[] = TaskRunCommand::CMD_NAME;
            $args[] = $schedule->getTaskId();

            if (null !== $schedule->getRunTimeEnv()) {
                $args[] = $this->buildRuntimeEnv($schedule);
            }

            return $args;
        }

        return $schedule->getShellCommand();
    }

    private function buildRuntimeEnv(Schedule $schedule)
    {
        $env = $schedule->getRunTimeEnv();

        if (is_array($env)) {
            $env = new ArrayRuntimeEnvironment($env);
        }

        $this->em->persist($env);
        $this->em->flush();
        return $env->getRuntimeEnvId();
    }

}
<?php


namespace Umbrella\CoreBundle\Component\Schedule;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;
use Umbrella\CoreBundle\Component\Schedule\Command\TaskRunCommand;
use Umbrella\CoreBundle\Component\Schedule\RuntimeEnv\AbstractEnvironment;
use Umbrella\CoreBundle\Component\Schedule\Task\TaskFactory;
use Umbrella\CoreBundle\Entity\ArrayRuntimeEnvironment;
use Umbrella\CoreBundle\Entity\Job;

/**
 * Class Schedule
 */
class Schedule
{
    /**
     * @var Scheduler
     */
    private $scheduler;

    /**
     * @var string
     */
    private $description;

    /**
     * @var int
     */
    private $timeout = 0;

    /**
     * @var array
     */
    private $shellCommand = [];

    /**
     * @var string|null
     */
    private $taskId = null;

    /**
     * @var mixed
     */
    private $runTimeEnv = null;

    /**
     * @var bool
     */
    private $disableOutput = false;

    /**
     * Schedule constructor.
     * @param Scheduler $scheduler
     */
    public function __construct(Scheduler $scheduler)
    {
        $this->scheduler = $scheduler;
    }

    /**
     * @param array $shellCommand
     * @return $this
     */
    public function setShellCommand(array $shellCommand): Schedule
    {
        $this->shellCommand = $shellCommand;
        return $this;
    }

    /**
     * @return array
     */
    public function getShellCommand(): array
    {
        return $this->shellCommand;
    }

    /**
     * @param $taskId
     * @return $this
     */
    public function setTask(string $taskId, $runTimeEnv = null): Schedule
    {
        $this->taskId = $taskId;
        $this->runTimeEnv = $runTimeEnv;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTaskId(): ?string
    {
        return $this->taskId;
    }

    /**
     * @return mixed|null
     */
    public function getRunTimeEnv()
    {
        return $this->runTimeEnv;
    }

    /**
     * @param int $timedout
     * @return $this
     */
    public function setTimeout(int $timeout = 0): Schedule
    {
        $this->timeout = $timeout;
        return $this;
    }

    /**
     * @return int
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }


    /**
     * @param $description
     * @return $this
     */
    public function setDescription(string $description): Schedule
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return bool
     */
    public function isDisableOutput(): bool
    {
        return $this->disableOutput;
    }

    /**
     * @param bool $disableOutput
     * @return $this
     */
    public function disableOutput(bool $disableOutput = true): Schedule
    {
        $this->disableOutput = $disableOutput;
        return $this;
    }

    /**
     * @return $this
     */
    public function validate(): Schedule
    {
        $this->scheduler->validateSchedule($this);
        return $this;
    }

    /**
     * @return int
     */
    public function register()
    {
        return $this->scheduler->registerSchedule($this);
    }


}
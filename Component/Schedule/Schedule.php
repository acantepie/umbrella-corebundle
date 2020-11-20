<?php

namespace Umbrella\CoreBundle\Component\Schedule;

use Umbrella\CoreBundle\Component\Schedule\Context\AbstractTaskContext;
use Umbrella\CoreBundle\Entity\ArrayTaskContext;

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
     * @var AbstractTaskContext|null
     */
    private $context = null;

    /**
     * @var bool
     */
    private $disableOutput = false;

    /**
     * Schedule constructor.
     *
     * @param Scheduler $scheduler
     */
    public function __construct(Scheduler $scheduler)
    {
        $this->scheduler = $scheduler;
    }

    /**
     * @param array $shellCommand
     *
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
     *
     * @return $this
     */
    public function setTask(string $taskId): Schedule
    {
        $this->taskId = $taskId;

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
     * @param AbstractTaskContext|null $context
     */
    public function setContext($context): Schedule
    {
        if (null === $context) {
            throw new \InvalidArgumentException('Context can\'t be null.');
        }

        if (is_array($context)) {
            $this->context = new ArrayTaskContext($context);

            return $this;
        }

        if (!is_a($context, AbstractTaskContext::class)) {
            throw new \InvalidArgumentException(sprintf('Context must be an instance of "%s"', AbstractTaskContext::class));
        }

        $this->context = $context;

        return $this;
    }

    /**
     * @return AbstractTaskContext|null
     */
    public function getContext(): ?AbstractTaskContext
    {
        return $this->context;
    }

    /**
     * @param int $timedout
     *
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
     *
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
     *
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

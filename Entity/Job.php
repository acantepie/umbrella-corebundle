<?php

namespace Umbrella\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Umbrella\CoreBundle\Model\IdTrait;
use Umbrella\CoreBundle\Model\TimestampTrait;

/**
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity
 * @ORM\Table("umbrella_job")
 */
class Job
{
    /** State if job is inserted, but not yet ready to be started. */
    const STATE_NEW = 'new';
    /** State if job is inserted, and might be started. */
    const STATE_PENDING = 'pending';
    /** State if job was never started, and will never be started. */
    const STATE_CANCELED = 'canceled';
    /** State if job was started and has not exited, yet. */
    const STATE_RUNNING = 'running';
    /** State if job exists with a successful exit code. */
    const STATE_FINISHED = 'finished';
    /** State if job exits with a non-successful exit code. */
    const STATE_FAILED = 'failed';
    /** State if job exceeds its configured maximum runtime */
    const STATE_TERMINATED = 'terminated';

    use IdTrait;
    use TimestampTrait;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    public $pid;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    public $description;

    /**
     * Process timeout (s)
     *
     * @var int
     * @ORM\Column(type="smallint", nullable=false)
     */
    public $timeout = 0;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false, options={"default": "new"})
     */
    public $state = self::STATE_NEW;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $startedAt;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $endedAt;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    public $disableOutput = false;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    public $stdOutput;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    public $errorOutput;

    /**
     * @var array
     * @ORM\Column(type="json", nullable=true)
     */
    public $processArgs = [];

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    public $contextId;

    public function clearOutput()
    {
        $this->stdOutput = '';
        $this->errorOutput = '';
    }

    /**
     * @param string $format
     *
     * @return int (s)
     */
    public function runtime($format = '%H:%I:%S')
    {
        if (null === $this->startedAt) {
            return null;
        }

        if (self::STATE_RUNNING === $this->state) {
            $date = new \DateTime('NOW');

            return $date->diff($this->startedAt)->format($format);
        }

        if (null === $this->endedAt) {
            return null;
        }

        return $this->endedAt->diff($this->startedAt)->format($format);
    }

    /**
     * @param $stdOutput
     */
    public function addStdOutput($stdOutput)
    {
        $this->stdOutput .= $stdOutput;
    }

    /**
     * @param $errorOutput
     */
    public function addErrorOutput($errorOutput)
    {
        $this->errorOutput .= $errorOutput;
    }

    public function isNew()
    {
        return self::STATE_NEW === $this->state;
    }

    public function isPending()
    {
        return self::STATE_PENDING === $this->state;
    }

    public function isRunning()
    {
        return self::STATE_RUNNING === $this->state;
    }

    public function isCanceled()
    {
        return self::STATE_CANCELED === $this->state;
    }

    public function isFailed()
    {
        return self::STATE_FAILED === $this->state;
    }

    public function isFinished()
    {
        return self::STATE_FINISHED === $this->state;
    }

    public function isTerminated()
    {
        return self::STATE_TERMINATED === $this->state;
    }

    public function isDone()
    {
        return $this->isCanceled() || $this->isFinished() || $this->isTerminated() || $this->isFailed();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->id;
    }
}

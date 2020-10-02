<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 13/09/18
 * Time: 00:02
 */

namespace Umbrella\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class TaskProcess
 *
 * Represent one run of a task
 *
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity()
 * @ORM\Table("umbrella_task")
 */
class Task extends BaseEntity
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

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=true)
     */
    public $pid;

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
    public $checkedAt;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $endedAt;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    public $output;

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
     * @var BaseTaskConfig
     * @ORM\ManyToOne(targetEntity="Umbrella\CoreBundle\Entity\BaseTaskConfig", inversedBy="tasks", cascade={"ALL"})
     * @ORM\JoinColumn(name="config_id", referencedColumnName="id", onDelete="CASCADE")
     */
    public $config;

    /**
     * @param  string $format
     * @return int    (s)
     */
    public function runtime($format = '%H:%I:%S')
    {
        if ($this->startedAt === null) {
            return null;
        }

        if ($this->state === self::STATE_RUNNING) {
            $date = new \DateTime('NOW');
            return $date->diff($this->startedAt)->format($format);
        }

        if ($this->endedAt === null) {
            return null;
        }

        return $this->endedAt->diff($this->startedAt)->format($format);
    }

    /**
     * @return string
     */
    public function getPidFilePath()
    {
        return sprintf('%s%s.pid', self::getPidDirPath(), $this->id);
    }

    /**
     * @return string
     */
    public static function getPidDirPath()
    {
        return sprintf('%s/var/tasks/', getcwd());
    }

    /**
     * @param $output
     */
    public function addOutput($output)
    {
        $this->output .= $output;
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

    public function isCanceled()
    {
        return self::STATE_CANCELED === $this->state;
    }

    public function isRunning()
    {
        return self::STATE_RUNNING === $this->state;
    }

    public function isFailed()
    {
        return self::STATE_FAILED === $this->state;
    }

    public function isFinished()
    {
        return self::STATE_FINISHED === $this->state;
    }

    /**
     * @return bool
     */
    public function canSchedule()
    {
        return $this->isNew() || $this->isPending();
    }

    /**
     * Task scheduled
     */
    public function scheduled()
    {
        $this->resetRun();
        $this->state = self::STATE_PENDING;
    }

    /**
     * @return bool
     */
    public function canCancel()
    {
        return $this->isNew() || $this->isPending();
    }

    /**
     * Task canceled
     */
    public function canceled()
    {
        $this->resetRun();
        $this->state = self::STATE_CANCELED;
    }

    /**
     * @return bool
     */
    public function canStart()
    {
        return $this->isPending();
    }

    /**
     * Task started
     */
    public function started()
    {
        $this->resetRun();
        $this->state = self::STATE_RUNNING;
        $this->startedAt = new \DateTime();
    }

    /**
     * Task started
     */
    public function checked()
    {
        $this->checkedAt = new \DateTime();
    }

    /**
     * FIXME: implementation
     * In case of task we be re run
     */
    private function resetRun()
    {
        $this->pid = null;
        $this->startedAt = null;
        $this->endedAt = null;
        $this->output = null;
        $this->errorOutput = null;
        $this->endedAt = null;
        $this->checkedAt = null;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->id;
    }
}

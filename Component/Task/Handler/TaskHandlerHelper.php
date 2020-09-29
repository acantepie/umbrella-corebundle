<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 18/01/19
 * Time: 21:10
 */

namespace Umbrella\CoreBundle\Component\Task\Handler;

use Doctrine\ORM\Query;
use Doctrine\ORM\EntityManagerInterface;
use Umbrella\CoreBundle\Entity\BaseTask;

/**
 * Class TaskHandlerHelper
 */
class TaskHandlerHelper
{
    /**
     * @var BaseTask
     */
    private $task;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var integer
     */
    private $max;

    /**
     * @var integer
     */
    private $progress;

    /**
     * @var integer
     */
    private $percent;

    /**
     * @var Query
     */
    private $progressQuery = null;

    /**
     * TaskHelper constructor.
     *
     * @param EntityManagerInterface $em
     * @param BaseTask               $task
     */
    public function __construct(EntityManagerInterface $em, BaseTask $task)
    {
        $this->em = $em;
        $this->task = $task;
    }

    /**
     * @return BaseTask
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * @param int $max
     */
    public function progressStart($max = 0)
    {
        $this->max = $max;
    }

    /**
     * @param int $step
     */
    public function progressAdvance($step = 1)
    {
        $this->progress += $step;
        if ($this->progress > $this->max) {
            $this->max = $this->progress;
        }
        $this->flushProgress();
    }

    public function progressFinish()
    {
        $this->progress = $this->max;
        $this->flushProgress();
    }

    private function flushProgress()
    {
        $percent = round($this->progress / $this->max * 100);
        if ($this->percent !== $percent) {
            $this->percent = $percent;

            if (null === $this->progressQuery) {
                $this->progressQuery = $this->em->createQueryBuilder()
                    ->update(get_class($this->task), 'e')
                    ->set('e.progress', ':progress')
                    ->where('e.id = :id')
                    ->getQuery();
            }

            $this->progressQuery->execute([
                'progress' => $percent,
                'id' => $this->task->id
            ]);
        }
    }
}

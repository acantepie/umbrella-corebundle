<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 18/01/19
 * Time: 21:10
 */

namespace Umbrella\CoreBundle\Component\Task\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Umbrella\CoreBundle\Entity\UmbrellaTask;

/**
 * Class TaskHandlerHelper
 */
class TaskHandlerHelper
{
    /**
     * @var UmbrellaTask
     */
    private $task;

    /**
     * @var array
     */
    private $parameters;

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
     * @var Query
     */
    private $paramsQuery = null;

    /**
     * TaskHelper constructor.
     *
     * @param EntityManagerInterface $em
     * @param UmbrellaTask $task
     */
    public function __construct(EntityManagerInterface $em, UmbrellaTask $task)
    {
        $this->em = $em;
        $this->task = $task;
        $this->parameters = $task->parameters;
    }

    /**
     * @return UmbrellaTask
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param $key
     * @return bool
     */
    public function hasParameter($key)
    {
        return isset($this->parameters[$key]);
    }

    /**
     * @param $key
     * @return mixed
     */
    public function getParameter($key)
    {
        return $this->parameters[$key];
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

    /**
     *
     */
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
                    ->update(get_class($this->task),'e')
                    ->set('e.progress', ':progress')
                    ->where('e.id = :id')
                    ->getQuery();
            }

            $this->progressQuery->execute(array(
                'progress' => $percent,
                'id' => $this->task->id
            ));
        }
    }

}

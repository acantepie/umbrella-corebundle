<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 19/01/19
 * Time: 15:40
 */

namespace Umbrella\CoreBundle\Component\Task\Pool;

use Symfony\Component\Process\Process;
use Umbrella\CoreBundle\Entity\UmbrellaTask;

/**
 * Class Pool
 */
class Pool implements \IteratorAggregate, \Countable
{
    /**
     * @var PoolItem[]
     */
    private $items = [];

    /**
     * @param UmbrellaTask $task
     * @param Process      $process
     */
    public function add(UmbrellaTask $task, Process $process)
    {
        $this->items[$task->getTaskId()] = new PoolItem($task, $process);
    }

    /**
     * @param UmbrellaTask $task
     */
    public function remove(UmbrellaTask $task)
    {
        unset($this->items[$task->getTaskId()]);
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return $this->count() === 0;
    }

    /**
     * @inheritdoc
     * @return PoolItem[]|\ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }

    /**
     * @inheritdoc
     */
    public function count()
    {
        return count($this->items);
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 19/01/19
 * Time: 15:40
 */

namespace Umbrella\CoreBundle\Component\Task\Pool;

use Symfony\Component\Process\Process;
use Umbrella\CoreBundle\Entity\BaseTask;

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
     * @param BaseTask $task
     * @param Process  $process
     */
    public function add(BaseTask $task, Process $process)
    {
        $this->items[$task->id] = new PoolItem($task, $process);
    }

    /**
     * @param BaseTask $task
     */
    public function remove(BaseTask $task)
    {
        unset($this->items[$task->id]);
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

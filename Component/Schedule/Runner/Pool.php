<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 19/01/19
 * Time: 15:40
 */

namespace Umbrella\CoreBundle\Component\Schedule\Runner;

use Symfony\Component\Process\Process;
use Umbrella\CoreBundle\Entity\Job;

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
     * Pool constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param Job     $job
     * @param Process $process
     */
    public function add(Job $job, Process $process)
    {
        $this->items[$job->id] = new PoolItem($job, $process);
    }

    /**
     * @param Job $job
     */
    public function remove(Job $job)
    {
        unset($this->items[$job->id]);
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return 0 === $this->count();
    }

    /**
     * {@inheritdoc}
     *
     * @return PoolItem[]|\ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->items);
    }
}

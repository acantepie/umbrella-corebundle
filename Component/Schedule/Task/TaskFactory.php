<?php

namespace Umbrella\CoreBundle\Component\Schedule\Task;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Class TaskFactory
 */
class TaskFactory
{
    /**
     * @var AbstractTask[]
     */
    private $tasks = [];

    /**
     * @param $id
     * @param AbstractTask $task
     */
    public function register($id, AbstractTask $task)
    {
        $this->tasks[$id] = $task;
    }

    /**
     * @param BaseTaskConfig $config
     *
     * @return AbstractTaskHandler
     */
    public function create($id)
    {
        if (isset($this->tasks[$id])) {
            return $this->tasks[$id];
        } else {
            throw new \InvalidArgumentException(sprintf("No task found with id '%s', task registered are %s.", $id, implode(', ', array_keys($this->tasks))));
        }
    }

    /**
     * @param $id
     * @return bool
     */
    public function has($id)
    {
        return isset($this->tasks[$id]);
    }

    /**
     * @return array
     */
    public function listIds()
    {
        return array_keys($this->tasks);
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 13/09/18
 * Time: 21:45
 */

namespace Umbrella\CoreBundle\Component\Task\Handler;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Umbrella\CoreBundle\Entity\UmbrellaTask;

/**
 * Class TaskHandlerFactory
 */
class TaskHandlerFactory
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var TaskHandlerInterface[]
     */
    private $handlers = array();

    /**
     * TaskHandlerFactory constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param array $ids
     */
    public function loadHandlers(array $ids = array())
    {
        foreach ($ids as $id) {
            $handler = $this->container->get($id);

            if (false === $handler) {
                continue;
            }

            if (!is_subclass_of($handler, TaskHandlerInterface::class)) {
                continue;
            }

            if (0 === preg_match('/^[a-z\_\.]+$/', $handler->getAlias())) {
                throw new \InvalidArgumentException("Handler $id has an invalid alias '{$handler->getAlias()}'");
            }

            $this->handlers[$handler->getAlias()] = $handler;
        }
    }

    /**
     * @param UmbrellaTask $task
     * @return TaskHandlerInterface
     */
    public function create(UmbrellaTask $task)
    {
        if (isset($this->handlers[$task->handlerAlias])) {
            $handler = $this->handlers[$task->handlerAlias];
            $handler->initialize(new TaskHandlerHelper($this->container->get('doctrine.orm.entity_manager'), $task));
            return $handler;
        } else {
            throw new \InvalidArgumentException(sprintf(
                "No task handler found with alias '%s', alias registered are %s.",
                $task->handlerAlias,
                implode(', ', array_keys($this->handlers))
            ));
        }
    }

    /**
     * @return array
     */
    public function listAliases()
    {
        return array_keys($this->handlers);
    }
}

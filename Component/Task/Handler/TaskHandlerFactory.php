<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 13/09/18
 * Time: 21:45
 */

namespace Umbrella\CoreBundle\Component\Task\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Umbrella\CoreBundle\Component\Action\ActionType;
use Umbrella\CoreBundle\Entity\BaseTaskConfig;

/**
 * Class TaskHandlerFactory
 */
class TaskHandlerFactory
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var AbstractTaskHandler[]
     */
    private $handlers = [];

    /**
     * TaskHandlerFactory constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param $id
     * @param ActionType $actionType
     */
    public function registerHandler($id, AbstractTaskHandler $handler)
    {
        $this->handlers[$id] = $handler;
    }

    /**
     * @param BaseTaskConfig $config
     *
     * @return AbstractTaskHandler
     */
    public function create(BaseTaskConfig $config)
    {
        if (isset($this->handlers[$config->handlerAlias])) {
            $handler = $this->handlers[$config->handlerAlias];

            return $handler;
        } else {
            throw new \InvalidArgumentException(sprintf("No task handler found with alias '%s', alias registered are %s.", $config->handlerAlias, implode(', ', array_keys($this->handlers))));
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

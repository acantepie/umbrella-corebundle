<?php

namespace Umbrella\CoreBundle\Component\FileWriter\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Umbrella\CoreBundle\Component\Task\Handler\AbstractTaskHandler;

/**
 * Class TaskFileWriterHandler
 */
class TaskFileWriterHandler extends AbstractTaskHandler
{
    /**
     * @var FileWriterHandlerFactory
     */
    private $handlerFactory;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * TaskFileWriterHandler constructor.
     * @param FileWriterHandlerFactory $handlerFactory
     * @param EntityManagerInterface   $em
     */
    public function __construct(FileWriterHandlerFactory $handlerFactory, EntityManagerInterface $em)
    {
        $this->handlerFactory = $handlerFactory;
        $this->em = $em;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $task = $this->taskHelper->getTask();
        $fwConfig = $task->fileWriterConfig;

        $handler = $this->handlerFactory->create($fwConfig);
        $handler->initialize($fwConfig);
        $handler->generate();

        $this->em->flush();
    }
}

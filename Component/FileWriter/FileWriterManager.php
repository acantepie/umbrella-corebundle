<?php

namespace Umbrella\CoreBundle\Component\FileWriter;

use Doctrine\ORM\EntityManagerInterface;
use Umbrella\CoreBundle\Entity\BaseTask;
use Umbrella\CoreBundle\Component\Task\TaskManager;
use Umbrella\CoreBundle\Entity\UmbrellaFileWriterConfig;
use Umbrella\CoreBundle\Component\FileWriter\Handler\TaskFileWriterHandler;
use Umbrella\CoreBundle\Component\FileWriter\Handler\FileWriterHandlerFactory;

/**
 * Class FileWriterManager
 */
class FileWriterManager
{
    /**
     * @var TaskManager
     */
    private $taskManager;

    /**
     * @var FileWriterHandlerFactory
     */
    private $handlerFactory;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * FileWriterService constructor.
     * @param TaskManager              $taskManager
     * @param FileWriterHandlerFactory $handlerFactory
     * @param EntityManagerInterface   $em
     */
    public function __construct(TaskManager $taskManager, FileWriterHandlerFactory $handlerFactory, EntityManagerInterface $em)
    {
        $this->taskManager = $taskManager;
        $this->handlerFactory = $handlerFactory;
        $this->em = $em;
    }

    /**
     * @param UmbrellaFileWriterConfig $config
     */
    public function schedule(UmbrellaFileWriterConfig $config)
    {
        $task = $this->taskManager->create(TaskFileWriterHandler::class);
        $task->fileWriterConfig = $config;
        $task->displayAsNotifiction = true;
        $task->type = BaseTask::TYPE_FILEWRITER;

        $this->em->flush();
    }

    /**
     * @param UmbrellaFileWriterConfig $config
     */
    public function run(UmbrellaFileWriterConfig $config)
    {
        $handler = $this->handlerFactory->create($fwConfig);
        $handler->initialize($fwConfig);
        $handler->execute();
        
        if (null === $config->outputFilePath) {
            throw new \RuntimeException(sprintf('You must set UmbrellaFileWriterConfig::ouputFilePath on handler %s', $config->handlerAlias));
        }
    }
}

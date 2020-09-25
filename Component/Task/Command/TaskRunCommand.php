<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 18/01/19
 * Time: 20:06
 */

namespace Umbrella\CoreBundle\Component\Task\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Umbrella\CoreBundle\Component\Task\Handler\TaskHandlerFactory;
use Umbrella\CoreBundle\Component\Task\TaskManager;

/**
 * Class TaskExecuteCommand
 */
class TaskRunCommand extends Command
{
    /**
     * @var string
     */
    const CMD_NAME = 'task:run';

    /**
     * @var TaskManager
     */
    private $taskManager;

    /**
     * @var TaskHandlerFactory
     */
    private $handlerFactory;

    /**
     * @var string
     */
    private $taskId;

    /**
     * TaskRunCommand constructor.
     * @param TaskManager $taskManager
     * @param TaskHandlerFactory $handlerFactory
     */
    public function __construct(TaskManager $taskManager, TaskHandlerFactory $handlerFactory)
    {
        $this->taskManager = $taskManager;
        $this->handlerFactory = $handlerFactory;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName(self::CMD_NAME);
        $this->addArgument('task_id', InputArgument::REQUIRED, 'Id of task to run');
        $this->setDescription('Run a task');
    }

    /**
     * @inheritdoc
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->taskId = $input->getArgument('task_id');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $task = $this->taskManager->getTask($this->taskId);
        if (null === $task) {
            throw new \RuntimeException(sprintf('No task %s registered', $this->taskId));
        }

        $output->setVerbosity($task->verbosityOutput);

        $handler = $this->handlerFactory->create($task);

        try {
            $handler->execute();
        } finally {
            $handler->destroy();
        }

        return 0;
    }


}
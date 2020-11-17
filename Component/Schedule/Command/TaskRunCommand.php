<?php

namespace Umbrella\CoreBundle\Component\Schedule\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Umbrella\CoreBundle\Component\Schedule\Context\EnvironmentProvider;
use Umbrella\CoreBundle\Component\Schedule\Context\TaskContextProvider;
use Umbrella\CoreBundle\Component\Schedule\Task\TaskFactory;

/**
 * Class TaskRunCommand
 */
class TaskRunCommand extends Command
{
    /** @var string  */
    const CMD_NAME = 'task:run';

    /**
     * @var TaskFactory
     */
    private $taskFactory;

    /**
     * @var TaskContextProvider
     */
    private $contextProvider;

    /**
     * TaskRunCommand constructor.
     *
     * @param TaskFactory $taskFactory
     * @param TaskContextProvider $contextProvider
     */
    public function __construct(TaskFactory $taskFactory, TaskContextProvider $contextProvider)
    {
        $this->taskFactory = $taskFactory;
        $this->contextProvider = $contextProvider;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName(self::CMD_NAME);
        $this->addArgument('task-id', InputArgument::REQUIRED, 'Id of task to run');
        $this->addArgument('context-id', InputArgument::REQUIRED, 'Id of run context');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $task = $this->taskFactory->create($input->getArgument('task-id'));
        $context = $this->contextProvider->getContext($input->getArgument('context-id'));

        $task->execute($context);

        return 0;
    }


}
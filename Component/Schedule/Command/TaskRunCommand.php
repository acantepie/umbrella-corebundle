<?php

namespace Umbrella\CoreBundle\Component\Schedule\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Umbrella\CoreBundle\Component\Schedule\RuntimeEnv\EnvironmentProvider;
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
     * @var EnvironmentProvider
     */
    private $envProvider;

    /**
     * TaskRunCommand constructor.
     * @param TaskFactory $taskFactory
     * @param EnvironmentProvider $envProvider
     */
    public function __construct(TaskFactory $taskFactory, EnvironmentProvider $envProvider)
    {
        $this->taskFactory = $taskFactory;
        $this->envProvider = $envProvider;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName(self::CMD_NAME);
        $this->addArgument('id', InputArgument::REQUIRED, 'Id of task to run');
        $this->addArgument('runtime-env', InputArgument::OPTIONAL, 'Id of runtime env');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $task = $this->taskFactory->create($input->getArgument('id'));

        $env = $input->getArgument('runtime-env')
            ? $this->envProvider->getEnvironment($input->getArgument('runtime-env'))
            : null;

        $task->execute($env);

        return 0;
    }


}
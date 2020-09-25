<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 06/03/20
 * Time: 22:04
 */

namespace Umbrella\CoreBundle\Component\Task\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Umbrella\CoreBundle\Component\Task\TaskManager;
use Umbrella\CoreBundle\Entity\UmbrellaTask;

/**
 * Class TaskRegisterCommand
 */
class TaskRegisterCommand extends Command
{
    /**
     * @var string
     */
    const CMD_NAME = 'task:register';

    /**
     * @var TaskManager
     */
    private $taskManager;

    /**
     * @var SymfonyStyle
     */
    private $io;

    /**
     * @var string
     */
    private $handlerAlias;

    /**
     * TaskRegisterCommand constructor.
     * @param TaskManager $taskManager
     */
    public function __construct(TaskManager $taskManager)
    {
        $this->taskManager = $taskManager;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName(self::CMD_NAME);
        $this->addArgument('handler_alias', InputArgument::REQUIRED, 'alias of handler to register');
        $this->setDescription('Register a task');
    }

    /**
     * @inheritdoc
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->handlerAlias = $input->getArgument('handler_alias');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $task = $this->taskManager->createTask($this->handlerAlias);
        $this->taskManager->register($task);

        $this->io->success(sprintf('Task registered with id %s', $task->getTaskId()));
        return 0;

    }


}
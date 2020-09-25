<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 15/09/18
 * Time: 13:10
 */

namespace Umbrella\CoreBundle\Component\Task\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Umbrella\CoreBundle\Component\Task\TaskManager;
use Umbrella\CoreBundle\Entity\UmbrellaTask;

/**
 * Class TaskListCommand
 * To see progress of running process: watch php bin/console task:list
 */
class TaskListCommand extends Command
{
    const CMD_NAME = 'task:list';

    /**
     * @var TaskManager
     */
    private $taskManager;

    /**
     * @var SymfonyStyle
     */
    private $io;

    /**
     * @var boolean
     */
    private $pending;

    /**
     * @var boolean
     */
    private $done;

    /**
     * TaskListCommand constructor.
     * @param TaskManager $taskManager
     */
    public function __construct(TaskManager $taskManager)
    {
        $this->taskManager = $taskManager;
        parent::__construct();
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this->setName(self::CMD_NAME);
        $this->setDescription("List tasks");
        $this->addOption('pending', null, InputOption::VALUE_NONE);
        $this->addOption('done', null, InputOption::VALUE_NONE);
    }

    /**
     * @inheritdoc
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->pending = $input->getOption('pending');
        $this->done = $input->getOption('done');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        // Task pending
        if ($this->pending) {
            $tasksPending = $this->taskManager->findByStates([UmbrellaTask::STATE_PENDING]);

            if (count($tasksPending) > 0) {
                $this->io->title('Tasks pending (' . count($tasksPending) . ')');
                $rows = [];

                foreach ($tasksPending as $task) {
                    $rows[] = array(
                        $task->getTaskId(),
                        $task->handlerAlias,
                        $task->createdAt->format('d/m/Y H:i:s')
                    );
                }
                $this->io->table(['id', 'handler', 'created'], $rows);
            }
        }


        $tasksRunning = $this->taskManager->findByStates([UmbrellaTask::STATE_RUNNING]);

        // Task running
        if (count($tasksRunning) > 0) {
            $this->io->title('Tasks running (' . count($tasksRunning) . ')');
            $rows = [];

            /** @var UmbrellaTask $task */
            foreach ($tasksRunning as $task) {
                $rows[] = array(
                    $task->getTaskId(),
                    $task->handlerAlias,
                    $task->pid,
                    $task->startedAt ? $task->startedAt->format('d/m/Y H:i:s') : '?',
                    $task->runtime(),
                    $task->progress ? $task->progress : ''
                );
            }
            $this->io->table(['id', 'handler', 'pid', 'started', 'runtime (s)', 'progress (%)'], $rows);
        }

        // Task done
        if ($this->done) {
            $tasksDone = $this->taskManager->findByStates([UmbrellaTask::STATE_FINISHED, UmbrellaTask::STATE_TERMINATED, UmbrellaTask::STATE_FAILED]);

            if (count($tasksDone) > 0) {
                $this->io->title('Tasks done (' . count($tasksDone) . ')');
                $rows = [];

                /** @var UmbrellaTask $task */
                foreach ($tasksDone as $task) {
                    $rows[] = array(
                        $task->getTaskId(),
                        $task->handlerAlias,
                        $task->startedAt ? $task->startedAt->format('d/m/Y H:i:s') : '?',
                        $task->endedAt ? $task->endedAt->format('d/m/Y H:i:s') : '?',
                        $task->runtime(),
                        $task->state
                    );
                }
                $this->io->table(['id', 'handler', 'started', 'ended', 'runtime (s)', 'status'], $rows);
            }
        }

        return 0;
    }

}
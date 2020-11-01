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
use Umbrella\CoreBundle\Entity\Task;

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
     * @var bool
     */
    private $pending;

    /**
     * @var bool
     */
    private $done;

    /**
     * TaskListCommand constructor.
     *
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
        $this->setDescription('List tasks');
        $this->addOption('pending', null, InputOption::VALUE_NONE);
        $this->addOption('done', null, InputOption::VALUE_NONE);
    }

    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->pending = $input->getOption('pending');
        $this->done = $input->getOption('done');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Task pending
        if ($this->pending) {
            $tasksPending = $this->taskManager->findByStates([Task::STATE_PENDING]);

            if (count($tasksPending) > 0) {
                $this->io->title('Tasks pending (' . count($tasksPending) . ')');
                $rows = [];

                foreach ($tasksPending as $task) {
                    $rows[] = [
                        $task->id,
                        $task->config->handlerAlias,
                        $task->config->tag,
                        $task->createdAt->format('d/m/Y H:i:s'),
                    ];
                }
                $this->io->table(['id', 'config (handler alias)', 'config (tag)', 'created'], $rows);
            }
        }

        $tasksRunning = $this->taskManager->findByStates([Task::STATE_RUNNING]);

        // Task running
        if (count($tasksRunning) > 0) {
            $this->io->title('Tasks running (' . count($tasksRunning) . ')');
            $rows = [];

            /** @var Task $task */
            foreach ($tasksRunning as $task) {
                $rows[] = [
                    $task->id,
                    $task->config->handlerAlias,
                    $task->config->tag,
                    $task->pid,
                    $task->startedAt ? $task->startedAt->format('d/m/Y H:i:s') : '?',
                    $task->runtime(),
                ];
            }
            $this->io->table(['id', 'config (handler alias)', 'config (tag)', 'pid', 'started', 'runtime (s)'], $rows);
        }

        // Task done
        if ($this->done) {
            $tasksDone = $this->taskManager->findByStates([Task::STATE_FINISHED, Task::STATE_TERMINATED, Task::STATE_FAILED]);

            if (count($tasksDone) > 0) {
                $this->io->title('Tasks done (' . count($tasksDone) . ')');
                $rows = [];

                /** @var Task $task */
                foreach ($tasksDone as $task) {
                    $rows[] = [
                        $task->id,
                        $task->config->handlerAlias,
                        $task->config->tag,
                        $task->startedAt ? $task->startedAt->format('d/m/Y H:i:s') : '?',
                        $task->endedAt ? $task->endedAt->format('d/m/Y H:i:s') : '?',
                        $task->runtime(),
                        $task->state,
                    ];
                }
                $this->io->table(['id', 'config (handler alias)', 'config (tag)', 'started', 'ended', 'runtime (s)', 'status'], $rows);
            }
        }

        return 0;
    }
}

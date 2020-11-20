<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 15/09/18
 * Time: 13:10
 */

namespace Umbrella\CoreBundle\Component\Schedule\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\ConsoleSectionOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Umbrella\CoreBundle\Component\Schedule\JobManager;
use Umbrella\CoreBundle\Entity\Job;

/**
 * Class ScheduleListCommand
 * List all job
 */
class ScheduleListCommand extends Command
{
    const CMD_NAME = 'schedule:list';

    /**
     * @var JobManager
     */
    private $jobManager;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var bool
     */
    private $_done;

    /**
     * @var bool
     */
    private $_watch;

    /**
     * ScheduleListCommand constructor.
     *
     * @param JobManager             $jobManager
     * @param EntityManagerInterface $em
     */
    public function __construct(JobManager $jobManager, EntityManagerInterface $em)
    {
        $this->jobManager = $jobManager;
        $this->em = $em;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName(self::CMD_NAME);
        $this->setDescription('List schedule');
        $this->addOption('done', 'd', InputOption::VALUE_NONE);
        $this->addOption('watch', 'w', InputOption::VALUE_NONE);
    }

    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->_done = $input->getOption('done');
        $this->_watch = $input->getOption('watch');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $section = $output->section();

        if ($this->_watch) {
            do {
                $section->clear();
                $this->render($section);
                $this->em->clear();
                sleep(1);
            } while (true);
        } else {
            $this->render($section);
        }

        return 0;
    }

    private function render(ConsoleSectionOutput $section)
    {
        $states = [
            $states[] = Job::STATE_PENDING,
            $states[] = Job::STATE_RUNNING,
        ];

        if ($this->_done) {
            $states[] = Job::STATE_FINISHED;
            $states[] = Job::STATE_TERMINATED;
            $states[] = Job::STATE_FAILED;
            $states[] = Job::STATE_CANCELED;
        }

        $jobs = $this->jobManager->getJobsByStates($states);

        $table = new Table($section);
        $table->setHeaderTitle(sprintf('%d jobs', count($jobs)));
        $table->setHeaders(['Etat', 'Id', 'Description', 'Date', 'Runtime', 'pid']);

        foreach ($jobs as $job) {
            $table->addRow([
                $this->_renderState($job->state),
                $job->id,
                $job->description,
                $job->updatedAt->format('d/m/Y H:i'),
                $job->runtime(),
                $job->pid,
            ]);
        }

        $table->render();
    }

    private function _renderState($state)
    {
        switch ($state) {
            case Job::STATE_PENDING:
                return sprintf('<fg=blue>%s</>', $state);

            case Job::STATE_RUNNING:
                return sprintf('<bg=blue>%s</>', $state);

            case Job::STATE_FAILED:
                return sprintf('<error>%s</error>', $state);

            default:
                return sprintf('%s', $state);
        }
    }

    // legacy
    private function __execute(InputInterface $input, OutputInterface $output)
    {
        // Task pending
        if ($this->pending) {
            $tasksPending = $this->taskManager->findByStates([Task::STATE_PENDING]);

            if (count($tasksPending) > 0) {
                $this->io->title('Tasks pending (' . count($tasksPending) . ')');
                $rows = [];

                foreach ($tasksPending as $task) {
                    $rows[] = [
                        '<info>' . $task->id . '</info>',
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

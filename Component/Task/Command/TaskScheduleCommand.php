<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 18/01/19
 * Time: 20:06
 */

namespace Umbrella\CoreBundle\Component\Task\Command;

use Symfony\Component\Process\Process;
use Umbrella\CoreBundle\Entity\BaseTask;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Umbrella\CoreBundle\Component\Task\Pool\Pool;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Console\Input\InputInterface;
use Umbrella\CoreBundle\Component\Task\TaskManager;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\ProcessTimedOutException;

/**
 * Class TaskExecuteCommand
 */
class TaskScheduleCommand extends Command
{
    use LockableTrait;

    /**
     * @var string
     */
    const CMD_NAME = 'task:schedule';

    /**
     * @var TaskManager
     */
    private $taskManager;

    /**
     * @var string
     */
    private $phpBinaryPath;

    /**
     * @var string
     */
    private $consolePath;

    /**
     * @var SymfonyStyle
     */
    private $io;

    /**
     * Interval in s
     * @var int
     */
    private $interval;

    /**
     * @var boolean
     */
    private $verbose;

    /**
     * @var Pool
     */
    private $pool;

    /**
     * @var boolean
     */
    private $interrupted = false;

    /**
     * TaskScheduleCommand constructor.
     * @param TaskManager $taskManager
     * @param $consolePath
     */
    public function __construct(TaskManager $taskManager, $consolePath)
    {
        $this->taskManager = $taskManager;
        $this->consolePath = $consolePath;
        $this->pool = new Pool();
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName(self::CMD_NAME);
        $this->setDescription('Schedule tasks');
        $this->addOption('interval', 'i', InputOption::VALUE_REQUIRED, 'Interval to check process in s', 1);
    }

    /**
     * @inheritdoc
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $phpBinaryFinder = new PhpExecutableFinder();
        $this->phpBinaryPath = $phpBinaryFinder->find();

        $this->io = new SymfonyStyle($input, $output);
        $this->interval = $input->getOption('interval');
        $this->verbose = $input->getOption('verbose');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // try lock
        if (!$this->lock()) {
            $output->writeln('The command is already running.');
            return 0;
        }

        $tasksToSchedule = $this->taskManager->getTasksToSchedule();
        if (count($tasksToSchedule) === 0) {
            if ($this->verbose) {
                $this->io->writeln('No task to schedule');
            }
            return 1;
        }

        // create pid path
        $pidPath = BaseTask::getPidDirPath();
        $fs = new Filesystem();
        if (!$fs->exists($pidPath)) {
            $fs->mkdir($pidPath);
        }

        // setup signal
        $this->setupSignalHandlers();

        // start task
        if ($this->verbose) {
            $this->io->title(sprintf('%d tasks to schedule', count($tasksToSchedule)));
        }

        foreach ($tasksToSchedule as $task) {
            $this->startTask($task);
        }

        // wait all done/killed
        while (!$this->pool->isEmpty()) {
            sleep($this->interval);
            pcntl_signal_dispatch();
            $this->checkRunningTasks();
        }

        $this->release();
        return 1;
    }

    /**
     * Start a task
     *
     * @param BaseTask $task
     */
    private function startTask(BaseTask $task)
    {
        // create process
        $process = new Process([
            $this->phpBinaryPath,
            $this->consolePath,
            TaskRunCommand::CMD_NAME,
            $task->id
        ]);

        // update state
        $task->started();

        // configure process
        $process->setTimeout($task->timeout);

        // start
        $process->start();
        $task->pid = $process->getPid();
        touch($task->getPidFilePath());

        $this->taskManager->update($task);

        // add to pool
        $this->pool->add($task, $process);

        if ($this->verbose) {
            $this->io->writeln(sprintf('[Started] %s', $task));
        }
    }

    /**
     * Check running tasks at each tick
     */
    private function checkRunningTasks()
    {
        foreach ($this->pool as $poolItem) {
            $process = $poolItem->process;
            $task = $poolItem->task;

            // update output
            $newStdOutput = substr($process->getOutput(), $poolItem->stdOutputCursor);
            $poolItem->incrStdOutputCursor(strlen($newStdOutput));

            $newErrorOutput = substr($process->getErrorOutput(), $poolItem->errorOutputCursor);
            $poolItem->incrErrorOutputCursor(strlen($newErrorOutput));

            $task->addStdOutput($newStdOutput);
            $task->addErrorOutput($newErrorOutput);
            $task->addOutput($newStdOutput);
            $task->addOutput($newErrorOutput);
            $task->checked();
            $this->taskManager->update($task);

            // task done ?
            if (!$process->isRunning()) {
                $task->endedAt = new \DateTime('NOW');

                if ($this->interrupted) {
                    $task->state = BaseTask::STATE_TERMINATED;
                } else {
                    $task->state = $process->isSuccessful() ? BaseTask::STATE_FINISHED : BaseTask::STATE_FAILED;
                }

                $this->taskManager->update($task);
                $process->stop();

                $this->pool->remove($task);
                if ($this->verbose) {
                    $process->isSuccessful()
                        ? $this->io->writeln(sprintf('[done] %s', $task))
                        : $this->io->writeln(sprintf('[fail] %s', $task));
                }
                continue;
            }

            // task timeout
            try {
                $process->checkTimeout();
            } catch (ProcessTimedOutException $e) {
                $task->endedAt = new \DateTime('NOW');
                $task->state = BaseTask::STATE_TERMINATED;
                $this->taskManager->update($task);

                $this->pool->remove($task);
                if ($this->verbose) {
                    $this->io->writeln(sprintf('[timeout] %s', $task));
                }
                continue;
            }

            // task killed
            if (!file_exists($task->getPidFilePath())) {
                $task->state = BaseTask::STATE_TERMINATED;
                $task->endedAt = new \DateTime('NOW');
                $this->taskManager->update($task);
                $process->stop();

                $this->pool->remove($task);
                if ($this->verbose) {
                    $this->io->writeln(sprintf('[stopped] %s', $task));
                }
                continue;
            }
        }
        gc_collect_cycles();
    }

    private function setupSignalHandlers()
    {
        pcntl_signal(SIGTERM, function () {
            if ($this->verbose) {
                $this->io->writeln('Received SIGTERM signal.');
            }
            $this->interrupted = true;
        });
        pcntl_signal(SIGINT, function () {
            if ($this->verbose) {
                $this->io->writeln('Received SIGINT signal.');
            }
            $this->interrupted = true;
        });
    }
}

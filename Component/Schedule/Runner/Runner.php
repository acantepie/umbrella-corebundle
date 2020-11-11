<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 18/01/19
 * Time: 20:06
 */

namespace Umbrella\CoreBundle\Component\Schedule\Runner;

use Psr\Log\LoggerInterface;
use Symfony\Component\Process\Exception\ProcessTimedOutException;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;
use Umbrella\CoreBundle\Component\Schedule\Command\TaskRunCommand;
use Umbrella\CoreBundle\Component\Schedule\JobManager;
use Umbrella\CoreBundle\Entity\Job;

/**
 * Class Runner
 */
class Runner
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var JobManager
     */
    private $jobManager;

    /**
     * @var Pool
     */
    private $pool;

    /**
     * @var bool
     */
    private $interrupted = false;

    /**
     * Runner constructor.
     * @param LoggerInterface $logger
     * @param JobManager $jobManager
     */
    public function __construct(LoggerInterface $logger, JobManager $jobManager)
    {
        $this->logger = $logger;
        $this->jobManager = $jobManager;
    }

    /**
     * @param array $jobIds
     */
    public function run($jobIds = [])
    {
        // init pool
        $this->pool = new Pool();

        // init php binary path


        // init sig
        $this->setupSignalHandlers();

        // start all job
        foreach ($this->jobManager->getPendingJobs($jobIds) as $job) {
            $process = new Process($job->processArgs);

            $job->state = Job::STATE_RUNNING;
            $job->startedAt = new \DateTime();
            $job->clearOutput();
            $process->setTimeout($job->timeout);

            if ($job->disableOutput) {
                $process->disableOutput();
            }

            // start process
            $process->start();
            $job->pid = $process->getPid();
            $this->jobManager->saveJob($job);

            $this->logJobState($job);

            // add to pool
            $this->pool->add($job, $process);
        }

        // wait all done/killed
        while (!$this->pool->isEmpty()) {
            sleep(1);
            pcntl_signal_dispatch();
            $this->checkRunningJobs();
        }
    }

    /**
     * Check running jobs at each tick
     */
    private function checkRunningJobs()
    {
        foreach ($this->pool as $poolItem) {
            $process = $poolItem->process;
            $job = $poolItem->job;

            // update output
            $poolItem->updateJobOutput();
            $this->jobManager->saveJob($job);

            // task done ?
            if (!$process->isRunning()) {
                $job->endedAt = new \DateTime('NOW');

                if ($this->interrupted) {
                    $job->state = Job::STATE_TERMINATED;
                } else {
                    $job->state = $process->isSuccessful() ? Job::STATE_FINISHED : Job::STATE_FAILED;
                }

                $this->jobManager->saveJob($job);
                $process->stop();

                $this->pool->remove($job);

                $this->logJobState($job);
            }

            // task timeout
            try {
                $process->checkTimeout();
            } catch (ProcessTimedOutException $e) {
                $job->endedAt = new \DateTime('NOW');
                $job->state = Job::STATE_TERMINATED;
                $this->jobManager->saveJob($job);

                $this->pool->remove($job);

                $this->logJobState($job, 'timedout');
                continue;
            }

        }
    }

    private function setupSignalHandlers()
    {
        pcntl_signal(SIGTERM, function () {
            $this->interrupted = true;
        });
        pcntl_signal(SIGINT, function () {
            $this->interrupted = true;
        });
    }

    /**
     * @param Job $job
     * @param string $message
     */
    private function logJobState(Job $job, $message = '')
    {
        $log = sprintf('[%s] job %s', $job->state, $job);
        if (!empty($message)) {
            $log .= sprintf(' - %s', $message);
        }
        $this->logger->info($log);
    }
}

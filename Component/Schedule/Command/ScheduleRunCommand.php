<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 18/01/19
 * Time: 20:06
 */

namespace Umbrella\CoreBundle\Component\Schedule\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Umbrella\CoreBundle\Component\Schedule\Runner\Runner;

/**
 * Class TaskScheduleCommand
 */
class ScheduleRunCommand extends Command
{
    use LockableTrait;

    /**
     * @var string
     */
    const CMD_NAME = 'schedule:run';

    /**
     * @var Runner
     */
    private $runner;

    /**
     * ScheduleRunCommand constructor.
     *
     * @param Runner $runner
     */
    public function __construct(Runner $runner)
    {
        $this->runner = $runner;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName(self::CMD_NAME);
        $this->setDescription('Run schedculer');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->lock()) {
            $output->writeln('Scheduler is already running.');

            return 0;
        }

        $this->runner->run();

        $this->release();

        return 0;
    }
}

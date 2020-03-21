<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 28/05/17
 * Time: 13:35.
 */

namespace Umbrella\CoreBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Umbrella\CoreBundle\Services\EntityIndexer;

/**
 * Class IndexEntityCommand.
 */
class IndexEntityCommand extends Command
{
    const CMD_NAME = 'umbrella:entity:index';

    /**
     * @var EntityIndexer
     */
    private $indexer;

    /**
     * IndexEntityCommand constructor.
     * @param EntityIndexer $indexer
     */
    public function __construct(EntityIndexer $indexer)
    {
        $this->indexer = $indexer;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        $this->setName(self::CMD_NAME);
    }

    /**
     * @inheritDoc
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $output->setVerbosity(OutputInterface::VERBOSITY_VERY_VERBOSE);
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->indexer->indexAll();
    }
}

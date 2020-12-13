<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 28/05/17
 * Time: 13:35.
 */

namespace Umbrella\CoreBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Umbrella\CoreBundle\Component\Search\EntityIndexer;

/**
 * Class IndexEntityCommand.
 */
class IndexEntityCommand extends Command
{
    const CMD_NAME = 'umbrella:entity:index';

    /**
     * @var SymfonyStyle
     */
    private $io;

    /**
     * @var EntityIndexer
     */
    private $indexer;

    /**
     * @var string
     */
    private $entityClass;

    /**
     * IndexEntityCommand constructor.
     *
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
    protected function configure()
    {
        $this->setName(self::CMD_NAME);
        $this->addArgument('entityClass', InputArgument::OPTIONAL, 'Entity class to index');
    }

    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
        $output->setVerbosity(OutputInterface::VERBOSITY_VERY_VERBOSE);
        $this->entityClass = $input->getArgument('entityClass');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($this->entityClass) {
            if (!$this->indexer->isSearchable($this->entityClass)) {
                $this->io->error(sprintf('Entity class %s is not indexable', $this->entityClass));

                return 1;
            }

            $this->indexer->indexAllOfClass($this->entityClass);

            return 0;
        } else {
            $this->indexer->indexAll();

            return 0;
        }
    }
}

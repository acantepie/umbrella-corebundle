<?php

namespace Umbrella\CoreBundle\Services;

use Psr\Log\LoggerInterface;
use Umbrella\CoreBundle\Utils\SQLUtils;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class EntityIndexer
 * @package Umbrella\CoreBundle\Services
 */
class EntityIndexer
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var SearchHandler
     */
    private $searchHandler;

    /**
     * EntityIndexer constructor.
     * @param EntityManagerInterface $em
     * @param LoggerInterface        $logger
     * @param SearchHandler          $searchHandler
     */
    public function __construct(EntityManagerInterface $em, LoggerInterface $logger, SearchHandler $searchHandler)
    {
        $this->em = $em;
        $this->logger = $logger;
        $this->searchHandler = $searchHandler;
    }

    public function indexAll($batchSize = 2000)
    {
        $entitiesClass = $this->em->getConfiguration()->getMetadataDriverImpl()->getAllClassNames();
        foreach ($entitiesClass as $entityClass) {
            if ($this->searchHandler->isSearchable($entityClass)) {
                try {
                    $this->indexEntity($entityClass);
                } catch (\Exception $ex) {
                    $this->logger->error($ex->getMessage());
                }
            }
        }
    }

    /**
     * @param $entityClass
     * @param mixed $batchSize
     */
    public function indexEntity($entityClass, $batchSize = 2000)
    {
        SQLUtils::disableSQLLog($this->em);

        $total = $this->em->createQuery("SELECT COUNT(e) FROM $entityClass e")->getSingleScalarResult();
        $count = 0;

        $this->logger->info('>> Index ' . $entityClass . ' : ' . $total);

        do {
            $iterable = $this->em->createQuery("SELECT e FROM $entityClass e")
                ->setFirstResult($count)
                ->setMaxResults($batchSize)
                ->iterate();

            $itCount = 0;

            while (($entity = $iterable->next()) !== false) {
                ++$count;
                ++$itCount;
                $this->searchHandler->indexEntity($entity[0]);
            }

            $this->logger->info('... ... ...' . $count);

            $this->em->flush();
            $this->em->clear();
            gc_collect_cycles();
        } while ($itCount >= $batchSize);
    }
}

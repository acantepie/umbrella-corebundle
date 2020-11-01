<?php

namespace Umbrella\CoreBundle\Services;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Mapping\MappingException;
use Psr\Log\LoggerInterface;
use Umbrella\CoreBundle\Utils\SQLUtils;

/**
 * Class EntityIndexer
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
     *
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

    /**
     * @param $entityClass
     *
     * @return bool
     */
    public function isIndexable($entityClass)
    {
        try {
            $md = $this->em->getClassMetadata($entityClass);
        } catch (MappingException $e) {
            return false;
        }

        if ($md->isMappedSuperclass) {
            return false;
        }

        if (!$this->searchHandler->isSearchable($entityClass)) {
            return false;
        }

        return true;
    }

    /**
     * @param int $batchSize
     */
    public function indexAll($batchSize = 2000)
    {
        $entitiesClass = $this->em->getConfiguration()->getMetadataDriverImpl()->getAllClassNames();
        foreach ($entitiesClass as $entityClass) {
            if ($this->isIndexable($entityClass)) {
                $this->indexEntity($entityClass, $batchSize);
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

        $this->logger->info(sprintf('>> Index %s', $entityClass));
        $query = $this->em->createQuery(sprintf('SELECT e FROM %s e', $entityClass));

        $i = 1;
        foreach ($query->iterate() as $row) {
            $entity = $row[0];
            $this->searchHandler->indexEntity($entity);

            if (($i % $batchSize) === 0) {
                $this->em->flush();
                $this->em->clear();
                $this->logger->info(sprintf('... ... ... %d', $i));
            }
            ++$i;
        }

        $this->em->flush();
        $this->em->clear();

        $this->logger->info(sprintf('> Total : %s', $i));
    }
}

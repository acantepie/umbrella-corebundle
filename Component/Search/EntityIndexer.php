<?php

namespace Umbrella\CoreBundle\Component\Search;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Mapping\MappingException;
use Psr\Log\LoggerInterface;
use Umbrella\CoreBundle\Component\Search\Annotation\SearchableAnnotationReader;
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
     * @var SearchableAnnotationReader
     */
    private $annotationReader;

    /**
     * EntityIndexer constructor.
     *
     * @param EntityManagerInterface     $em
     * @param LoggerInterface            $logger
     * @param SearchableAnnotationReader $annotationReader
     */
    public function __construct(EntityManagerInterface $em, LoggerInterface $logger, SearchableAnnotationReader $annotationReader)
    {
        $this->em = $em;
        $this->logger = $logger;
        $this->annotationReader = $annotationReader;
    }

    public function isSearchable(string $class): bool
    {
        try {
            $md = $this->em->getClassMetadata($class);
        } catch (MappingException $e) {
            return false;
        }

        if ($md->isMappedSuperclass) {
            return false;
        }

        if (!$this->isSearchableEntityClass($class)) {
            return false;
        }

        return true;
    }

    public function isSearchableEntityClass(string $entityClass): bool
    {
        return null !== $this->annotationReader->getSearchable($entityClass);
    }

    public function indexAll(int $batchSize = 2000): void
    {
        $entitiesClass = $this->em->getConfiguration()->getMetadataDriverImpl()->getAllClassNames();
        foreach ($entitiesClass as $entityClass) {
            if ($this->isSearchable($entityClass)) {
                $this->indexEntity($entityClass, $batchSize);
            }
        }
    }

    public function indexAllOfClass(string $entityClass, int $batchSize = 2000): void
    {
        SQLUtils::disableSQLLog($this->em);

        $this->logger->info(sprintf('>> Index %s', $entityClass));
        $query = $this->em->createQuery(sprintf('SELECT e FROM %s e', $entityClass));

        $i = 1;
        foreach ($query->iterate() as $row) {
            $entity = $row[0];
            $this->indexEntity($entity);

            if (0 === ($i % $batchSize)) {
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

    /**
     * @param $entity
     *
     * @return bool
     */
    public function indexEntity(object $entity): bool
    {
        $entityClass = get_class($entity);

        $searchable = $this->annotationReader->getSearchable($entityClass);

        // Entity doesn't have annotation Searchable
        if (null === $searchable) {
            return false;
        }

        $searches = [];
        foreach ($this->annotationReader->getSearchableProperties($entityClass) as $property => $annotation) {
            $searches[] = (string) $entity->{$property};
        }

        foreach ($this->annotationReader->getSearchableMethods($entityClass) as $method => $annotation) {
            $searches[] = (string) call_user_func([$entity, $method]);
        }

        $search = implode(' ', $searches);
        $entity->{$searchable->getSearchField()} = $search;

        return true;
    }
}

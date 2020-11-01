<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 01/06/17
 * Time: 23:52
 */

namespace Umbrella\CoreBundle\Services;

use Umbrella\CoreBundle\Annotation\SearchableAnnotationReader;

/**
 * Class SearchableHandler
 */
class SearchHandler
{
    /**
     * @var SearchableAnnotationReader
     */
    private $reader;

    /**
     * SearchHandler constructor.
     *
     * @param SearchableAnnotationReader $reader
     */
    public function __construct(SearchableAnnotationReader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * @param $entityClass
     *
     * @return bool
     */
    public function isSearchable($entityClass)
    {
        return null !== $this->reader->getSearchable($entityClass);
    }

    /**
     * @param $entity
     *
     * @return bool
     */
    public function indexEntity($entity)
    {
        $entityClass = get_class($entity);

        $searchable = $this->reader->getSearchable($entityClass);

        // Entity doesn't have annotation Searchable
        if (null === $searchable) {
            return false;
        }

        $searches = [];
        foreach ($this->reader->getSearchableProperties($entityClass) as $property => $annotation) {
            $searches[] = (string) $entity->{$property};
        }

        foreach ($this->reader->getSearchableMethods($entityClass) as $method => $annotation) {
            $searches[] = (string) call_user_func([$entity, $method]);
        }

        $search = implode(' ', $searches);
        $entity->{$searchable->getSearchField()} = $search;

        return true;
    }
}

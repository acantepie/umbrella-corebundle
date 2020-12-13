<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 01/06/17
 * Time: 23:40
 */

namespace Umbrella\CoreBundle\Component\Search\Annotation;

use Doctrine\Common\Annotations\Reader;

/**
 * Class SearchableAnnotationReader
 */
class SearchableAnnotationReader
{
    /**
     * @var Reader
     */
    private $reader;

    /**
     * SearchableAnnotationReader constructor.
     *
     * @param Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    public function getSearchable(string $entityClass) : ?Searchable
    {
        $reflection = new \ReflectionClass($entityClass);
        return $this->reader->getClassAnnotation($reflection, Searchable::class);
    }

    /**
     * @param $entityClass
     *
     * @return SearchableField[]
     */
    public function getSearchableProperties(string $entityClass) : array
    {
        $reflection = new \ReflectionClass($entityClass);

        $result = [];
        foreach ($reflection->getProperties() as $property) {
            $annotation = $this->reader->getPropertyAnnotation($property, SearchableField::class);
            if (null !== $annotation) {
                $result[$property->getName()] = $annotation;
            }
        }

        return $result;
    }

    /**
     * @param $entityClass
     *
     * @return SearchableField[]
     */
    public function getSearchableMethods(string $entityClass) : array
    {
        $reflection = new \ReflectionClass($entityClass);

        $result = [];
        foreach ($reflection->getMethods() as $method) {
            $annotation = $this->reader->getMethodAnnotation($method, SearchableField::class);
            if (null !== $annotation) {
                $result[$method->getName()] = $annotation;
            }
        }

        return $result;
    }
}

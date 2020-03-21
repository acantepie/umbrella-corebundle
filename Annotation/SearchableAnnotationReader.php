<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 01/06/17
 * Time: 23:40
 */

namespace Umbrella\CoreBundle\Annotation;

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
     * @param Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * @param $entityClass
     * @return null|Searchable
     */
    public function getSearchable($entityClass)
    {
        return $this->getInheritAnnotation($entityClass, Searchable::class);
    }

    /**
     * @param $entityClass
     * @return SearchableField[]
     */
    public function getSearchableProperties($entityClass)
    {
        $reflection = new \ReflectionClass($entityClass);

        $result = array();
        foreach ($reflection->getProperties() as $property) {
            $annotation = $this->reader->getPropertyAnnotation($property, SearchableField::class);
            if ($annotation !== null) {
                $result[$property->getName()] = $annotation;
            }
        }

        return $result;
    }

    /**
     * @param $entityClass
     * @return SearchableField[]
     */
    public function getSearchableMethods($entityClass)
    {
        $reflection = new \ReflectionClass($entityClass);

        $result = array();
        foreach ($reflection->getMethods() as $method) {
            $annotation = $this->reader->getMethodAnnotation($method, SearchableField::class);
            if ($annotation !== null) {
                $result[$method->getName()] = $annotation;
            }
        }

        return $result;
    }

    /* Helper */

    /**
     * @param $class
     * @param $annotationName
     * @return null|mixed
     */
    public function getInheritAnnotation($class, $annotationName)
    {
        $reflection = new \ReflectionClass($class);
        $annotation = $this->reader->getClassAnnotation($reflection, $annotationName);

        if ($annotation !== null) {
            return $annotation;
        }

        $parentClass = get_parent_class($class);
        return $parentClass === false
            ? null
            : $this->getInheritAnnotation($parentClass, $annotationName);
    }

}
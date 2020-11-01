<?php

namespace Umbrella\CoreBundle\Form\DataTransformer;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * Data transformer for multiple mode (i.e., multiple = true)
 *
 * Class EntitiesToPropertyTransformer
 */
class EntitiesToPropertyTransformer implements DataTransformerInterface
{
    /** @var EntityManagerInterface */
    protected $em;
    /** @var string */
    protected $className;
    /** @var string */
    protected $textProperty;
    /** @var string */
    protected $primaryKey;
    /** @var PropertyAccessor */
    protected $accessor;

    /**
     * @param EntityManagerInterface $em
     * @param string                 $class
     * @param string|null            $textProperty
     * @param string                 $primaryKey
     */
    public function __construct(EntityManagerInterface $em, $class, $textProperty = null, $primaryKey = 'id')
    {
        $this->em = $em;
        $this->className = $class;
        $this->textProperty = $textProperty;
        $this->primaryKey = $primaryKey;
        $this->accessor = PropertyAccess::createPropertyAccessor();
    }

    /**
     * Transform initial entities to array
     *
     * @param mixed $entities
     *
     * @return array
     */
    public function transform($entities)
    {
        if (empty($entities)) {
            return [];
        }

        $data = [];

        foreach ($entities as $entity) {
            $text = is_null($this->textProperty)
                ? (string) $entity
                : $this->accessor->getValue($entity, $this->textProperty);

            if ($this->em->contains($entity)) {
                $value = (string) $this->accessor->getValue($entity, $this->primaryKey);
            }

            $data[$value] = $text;
        }

        return $data;
    }

    /**
     * Transform array to a collection of entities
     *
     * @param array $values
     *
     * @return array
     */
    public function reverseTransform($values)
    {
        if (!is_array($values) || empty($values)) {
            return [];
        }

        // get multiple entities with one query
        $entities = $this->em->createQueryBuilder()
            ->select('entity')
            ->from($this->className, 'entity')
            ->where('entity.' . $this->primaryKey . ' IN (:ids)')
            ->setParameter('ids', $values)
            ->getQuery()
            ->getResult();

        // this will happen if the form submits invalid data
        if (count($entities) != count($values)) {
            throw new TransformationFailedException('One or more id values are invalid');
        }

        return $entities;
    }
}

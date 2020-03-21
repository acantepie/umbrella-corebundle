<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 21/07/18
 * Time: 15:45
 */

namespace Umbrella\CoreBundle\Component\DataTable\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Class DataTableCollection
 */
class DataTableCollection extends ArrayCollection
{

    /**
     * @var PropertyAccess
     */
    private $propertyAccessor;

    /**
     * DataTableCollection constructor.
     * @param array $elements
     */
    public function __construct(array $elements = array())
    {
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
        parent::__construct($elements);
    }

    /**
     * @param $property
     * @param string $direction
     * @return DataTableCollection
     */
    public function sortBy($property, $direction = 'ASC')
    {
        $iterator = $this->getIterator();
        $iterator->uasort(function ($a, $b) use($property, $direction) {
            if ($direction == 'ASC') {
                return $this->propertyAccessor->getValue($a, $property) < $this->propertyAccessor->getValue($b, $property) ? -1 : 1;
            } else {
                return $this->propertyAccessor->getValue($a, $property) < $this->propertyAccessor->getValue($b, $property) ? 1 : -1;
            }
        });
        return new DataTableCollection(iterator_to_array($iterator));
    }

}
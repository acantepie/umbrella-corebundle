<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 12/04/18
 * Time: 17:00
 */

namespace Umbrella\CoreBundle\Component\Column\Type;

/**
 * Class PercentColumnType
 */
class PercentColumnType extends PropertyColumnType
{

    /**
     * @param $entity
     * @param array $options
     * @return string
     */
    public function render($entity, array $options)
    {
        $value = (float) $this->accessor->getValue($entity, $options['property_path']);
        return $value * 100 . ' %';
    }
}
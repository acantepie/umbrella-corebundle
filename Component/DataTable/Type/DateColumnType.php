<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 21/05/17
 * Time: 10:44.
 */

namespace Umbrella\CoreBundle\Component\DataTable\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\Exception\UnexpectedTypeException;

/**
 * Class DateColumnType.
 */
class DateColumnType extends PropertyColumnType
{
    /**
     * @param $entity
     * @param array $options
     * @return mixed|string
     */
    public function render($entity, array $options)
    {
        try {
            $value = $this->accessor->getValue($entity, $options['property_path']);
        } catch (UnexpectedTypeException $e) {
            $value = null;
        }

        if ($value instanceof \DateTime) {
            return $value->format($options['format']);
        } else {
            return $value;
        }
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefault('format', 'd/m/Y')
            ->setAllowedTypes('format', 'string');
    }
}

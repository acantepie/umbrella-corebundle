<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 21/05/17
 * Time: 10:44.
 */

namespace Umbrella\CoreBundle\Component\Column\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\Exception\UnexpectedTypeException;

/**
 * Class DateColumnType.
 */
class DateColumnType extends PropertyColumnType
{
    /**
     * @inheritdoc
     */
    public function render($entity, array $options)
    {
        $value = $this->accessor->getValue($entity, $options['property_path']);
        return $value instanceof \DateTime ? $value->format($options['format']) : $value;
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

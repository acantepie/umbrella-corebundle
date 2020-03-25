<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 14/05/17
 * Time: 19:09.
 */

namespace Umbrella\CoreBundle\Component\DataTable\Type;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Class PropertyColumnType.
 */
class PropertyColumnType extends ColumnType
{

    /**
     * @var PropertyAccess
     */
    protected $accessor;

    /**
     * PropertyColumn constructor.
     */
    public function __construct()
    {
        $this->accessor = PropertyAccess::createPropertyAccessorBuilder()
            ->disableExceptionOnInvalidPropertyPath()
            ->getPropertyAccessor();
    }

    /**
     * @param $entity
     * @param array $options
     * @return string
     */
    public function render($entity, array $options)
    {
        return (string)$this->accessor->getValue($entity, $options['property_path']);
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('property_path', function (Options $options) {
                return $options['id'];
            })
            ->setAllowedTypes('property_path', 'string')

            ->setDefault('order_by', function (Options $options) {
                return $options['property_path'];
            })

            ->setDefault('renderer', [$this, 'render']);
    }
}

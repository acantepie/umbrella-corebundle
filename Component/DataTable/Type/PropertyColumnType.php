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
        $this->accessor = PropertyAccess::createPropertyAccessor();
    }

    /**
     * @param $entity
     * @param array $options
     * @return string
     */
    public function render($entity, array $options)
    {
        try {
            return (string) $this->accessor->getValue($entity, $options['property_path']);
        }
        catch (\Exception $ex) {
            return "";
        }
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(array(
            'property_path',
        ));
        $resolver->setAllowedTypes('property_path', 'string');
        $resolver->setDefault('renderer', [$this, 'render']);
        $resolver->setDefault('orderable', true);
        $resolver->setDefault('property_path', function (Options $options) {
            return $options['id'];
        });
        $resolver->setDefault('order_by', function (Options $options) {
            return $options['property_path'];
        });
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 28/05/17
 * Time: 15:15.
 */

namespace Umbrella\CoreBundle\Component\DataTable\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Class SequenceColumn.
 */
class RelocateColumnType extends PropertyColumnType
{

    /**
     * @param $entity
     * @param array $options
     * @return string
     */
    public function render($entity, array $options)
    {
        $value =  (string) $this->accessor->getValue($entity, $options['property_path']);
        return '<span data-sequence="' . $value . '"><i class="material-icons">drag_handle</i></span>';

    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefault('orderable', false);
        $resolver->setDefault('order', 'ASC');
        $resolver->setDefault('style', array(
            'width' => '10px'
        ));
        $resolver->setDefault('renderer', [$this, 'render']);
        $resolver->setDefault('label', '');
    }
}

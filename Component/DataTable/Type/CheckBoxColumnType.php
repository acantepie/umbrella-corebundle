<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 16/04/18
 * Time: 19:30
 */

namespace Umbrella\CoreBundle\Component\DataTable\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CheckBoxColumnType
 */
class CheckBoxColumnType extends ColumnType
{

    /**
     * @param $entity
     * @param array $options
     * @return string
     */
    public function render($entity, array $options)
    {
        return '<input type="checkbox">';
    }

    /**
     * @param array $options
     * @return string
     */
    public function renderLabel(array $options)
    {
        return '<input type="checkbox">';
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('order_by', null);
        $resolver->setDefault('class', 'text-center disable-row-click js-select');
        $resolver->setDefault('renderer', [$this, 'render']);
        $resolver->setDefault('label_renderer', [$this, 'renderLabel']);
    }
}
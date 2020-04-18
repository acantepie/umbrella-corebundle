<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 28/05/17
 * Time: 15:15.
 */

namespace Umbrella\CoreBundle\Component\Column\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Utils\HtmlUtils;

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
        return sprintf('<span data-sequence="%d">%s</span>', $value, HtmlUtils::render_icon($options['icon']));
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver
            ->setDefault('icon', 'mdi mdi-menu')
            ->setAllowedTypes('icon', 'string')
            ->setDefault('order_by', null)
            ->setDefault('order', 'ASC')
            ->setDefault('width', '10px')
            ->setDefault('renderer', [$this, 'render'])
            ->setDefault('label', '');
    }
}

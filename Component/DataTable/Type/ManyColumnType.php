<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 16/04/18
 * Time: 14:02
 */

namespace Umbrella\CoreBundle\Component\DataTable\Type;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Class ManyColumnType
 */
class ManyColumnType extends ColumnType
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
        $many = $this->accessor->getValue($entity, $options['many_path']);
        if (!is_iterable($many)) {
            throw new \InvalidArgumentException("Attribute {$options['many_path']} must be iterable.");
        }

        $html = "";
        foreach ($many as $one) {
            $html .= call_user_func($options['one_renderer'], $one, $options);
        }
        return $html;
    }

    /**
     * @param $one
     * @param array $options
     * @return string
     */
    public function renderOne($one, array $options)
    {
        $value = isset($options['one_path']) && !empty($options['one_path'])
            ? $this->accessor->getValue($one, $options['one_path'])
            : $one;

        return '<span class="badge badge-feather-primary mb-1 mt-1">' . $value . '</span>';
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(array(
            'one_path',

        ));

        $resolver->setRequired(array(
            'many_path',
            'one_renderer'
        ));

        $resolver->setAllowedTypes('many_path', 'string');
        $resolver->setAllowedTypes('one_path', 'string');
        $resolver->setAllowedTypes('one_renderer', 'callable');

        $resolver->setDefault('renderer', [$this, 'render']);
        $resolver->setDefault('one_renderer', [$this, 'renderOne']);

        $resolver->setDefault('many_path', function (Options $options) {
            return $options['id'];
        });
    }



}
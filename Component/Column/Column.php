<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 13/05/17
 * Time: 12:46.
 */

namespace Umbrella\CoreBundle\Component\Column;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Component\Column\Type\ColumnType;
use Umbrella\CoreBundle\Component\ComponentView;

/**
 * Class Column.
 */
class Column
{
    /**
     * @var ColumnType
     */
    private $type;

    /**
     * @var array
     */
    private $options;

    /**
     * @param $data
     *
     * @return string
     */
    public function render($data)
    {
        if (is_callable($this->options['renderer'])) {
            return call_user_func($this->options['renderer'], $data, $this->options);
        } else {
            return (string) $data;
        }
    }

    /**
     * @param ColumnType $type
     */
    public function setType(ColumnType $type)
    {
        $this->type = $type;
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options = [])
    {
        $this->options = $options;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired('id')
            ->setAllowedTypes('id', 'string')

            ->setDefault('label', function (Options $options) {
                return $options['id'];
            })
            ->setAllowedTypes('label', ['null', 'string'])

            ->setDefault('label_prefix', 'table.')
            ->setAllowedTypes('label_prefix', ['null', 'string'])

            ->setDefault('translation_domain', 'messages')
            ->setAllowedTypes('translation_domain', ['null', 'string'])

            ->setDefault('order', true)
            ->setAllowedValues('order', [false, true, 'ASC', 'DESC'])

            ->setDefault('order_by', null)
            ->setAllowedTypes('order_by', ['null', 'string', 'array'])

            ->setDefault('class', null)
            ->setAllowedTypes('class', ['null', 'string'])

            ->setDefault('width', null)
            ->setAllowedTypes('width', ['null', 'string'])

            ->setDefault('renderer', null)
            ->setAllowedTypes('renderer', ['null', 'callable']);
    }

    /**
     * @return array
     */
    public function getJsOptions()
    {
        return [
            'orderable' => false !== $this->options['order'] && null !== $this->options['order_by'],
            'className' => $this->options['class'],
        ];
    }

    /**
     * @return strine|false|null
     */
    public function getOrder()
    {
        return $this->options['order'];
    }

    /**
     * @return array
     */
    public function getOrderBy()
    {
        return (array) $this->options['order_by'];
    }

    /**
     * @return ComponentView
     */
    public function createView(): ComponentView
    {
        $componentView = new ComponentView();
        $componentView->template = '@UmbrellaCore/DataTable/column_header.html.twig';

        $componentView->vars['attr'] = [
            'class' => $this->options['class'],
            'style' => $this->options['width'] ? sprintf('width:%s', $this->options['width']) : null,
        ];

        $componentView->vars['label'] = $this->options['label'];
        $componentView->vars['label_prefix'] = $this->options['label_prefix'];
        $componentView->vars['translation_domain'] = $this->options['translation_domain'];

        return $componentView;
    }
}

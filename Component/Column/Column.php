<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 13/05/17
 * Time: 12:46.
 */

namespace Umbrella\CoreBundle\Component\Column;

use Symfony\Component\OptionsResolver\Options;
use Umbrella\CoreBundle\Model\OptionsAwareInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class Column.
 */
class Column implements OptionsAwareInterface
{
    /**
     * @var array
     */
    private $options;

    /**
     * @param $data
     * @return string
     */
    public function render($data)
    {
        if (is_callable($this->options['renderer'])) {
            return call_user_func($this->options['renderer'], $data, $this->options);
        } else {
            return (string)$data;
        }
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

            ->setDefault('default_order', null)
            ->setAllowedValues('default_order', [null, 'ASC', 'DESC'])

            ->setDefault('orderable', true)
            ->setAllowedTypes('orderable', ['bool'])

            ->setDefault('order_by', null)
            ->setAllowedTypes('order_by', ['null', 'string', 'array'])

            ->setDefault('class', null)
            ->setAllowedTypes('class', ['null', 'string'])

            ->setDefault('width', null)
            ->setAllowedTypes('width', ['null', 'string']);
    }

    /**
     * @return array
     */
    public function getViewOptions()
    {
        return [
            'label' => $this->options['label'],
            'label_prefix' => $this->options['label_prefix'],
            'translation_domain' => $this->options['translation_domain'],
            'class' => $this->options['class'],
            'width' => $this->options['width']
        ];
    }

    /**
     * @return array
     */
    public function getColumnsOptions()
    {
        return [
            'orderable' => $this->options['orderable'] && $this->options['order_by'] !== null,
            'className' => $this->options['class']
        ];
    }

    /**
     * @return string
     */
    public function getDefaultOrder()
    {
        return $this->options['default_order'];
    }

    /**
     * @return array
     */
    public function getOrderBy()
    {
        return (array) $this->options['order_by'];
    }
}

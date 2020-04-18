<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 14/05/17
 * Time: 19:10.
 */

namespace Umbrella\CoreBundle\Component\Column\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Component\RowAction\UmbrellaRowActionBuilder;
use Umbrella\CoreBundle\Component\RowAction\UmbrellaRowActionRenderer;

/**
 * Class ActionColumn.
 */
class ActionColumnType extends ColumnType
{
    /**
     * @var UmbrellaRowActionRenderer
     */
    protected $renderer;

    /**
     * ActionColumnType constructor.
     * @param UmbrellaRowActionRenderer $renderer
     */
    public function __construct(UmbrellaRowActionRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * @param $entity
     * @param array $options
     * @return string
     */
    public function render($entity, array $options)
    {
        $builder = new UmbrellaRowActionBuilder();
        if (is_callable($options['action_builder'])) {
            call_user_func($options['action_builder'], $builder, $entity);
        }

        $html = '';
        foreach ($builder->getActions() as $action) {
            $html .= $this->renderer->render($action);
        }
        return $html;
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefault('action_builder', null)
            ->setAllowedTypes('action_builder', array('null', 'callable'))

            ->setDefault('class', 'disable-row-click text-right')
            ->setDefault('width', '100px')
            ->setDefault('label', '')
            ->setDefault('renderer', [$this, 'render']);
    }
}

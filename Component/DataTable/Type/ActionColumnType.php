<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 14/05/17
 * Time: 19:10.
 */

namespace Umbrella\CoreBundle\Component\DataTable\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Component\RowAction\UmbrellaRowAction;
use Umbrella\CoreBundle\Component\RowAction\UmbrellaRowActionFactory;

/**
 * Class ActionColumn.
 */
class ActionColumnType extends ColumnType
{
    /**
     * @var UmbrellaRowActionFactory
     */
    protected $rowActionFactory;

    /**
     * ActionColumnType constructor.
     * @param UmbrellaRowActionFactory $rowActionFactory
     */
    public function __construct(UmbrellaRowActionFactory $rowActionFactory)
    {
        $this->rowActionFactory = $rowActionFactory;
    }

    /**
     * @param $entity
     * @param array $options
     * @return string
     */
    public function render($entity, array $options)
    {
        $actions = array();
        if (is_callable($options['action_builder'])) {
            $actions = call_user_func($options['action_builder'], $this->rowActionFactory, $entity);
        }

        if (!$actions) {
            return '';
        }

        $html = '';
        /** @var UmbrellaRowAction $action */
        foreach ($actions as $action) {
            $html .= $action->render();
        }
        return $html;
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefined(array(
            'action_builder'
        ));

        $resolver->setAllowedTypes('action_builder', array('null', 'callable'));

        $resolver->setDefault('class', 'disable-row-click text-right');
        $resolver->setDefault('width', '80px');
        $resolver->setDefault('label', '');
        $resolver->setDefault('renderer', [$this, 'render']);
    }
}

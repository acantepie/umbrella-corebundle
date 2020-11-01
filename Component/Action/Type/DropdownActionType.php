<?php

namespace Umbrella\CoreBundle\Component\Action\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Component\Action\ActionListFactory;
use Umbrella\CoreBundle\Component\ComponentView;

/**
 * Class DropdownActionType
 */
class DropdownActionType extends ActionType
{
    /**
     * @var ActionListFactory
     */
    private $actionListFactory;

    /**
     * DropdownActionType constructor.
     *
     * @param ActionListFactory $actionListFactory
     */
    public function __construct(ActionListFactory $actionListFactory)
    {
        $this->actionListFactory = $actionListFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(ComponentView $view, array $options)
    {
        $view->vars['attr'] = [
            'class' => $options['class'],
        ];

        $view->vars['dropdown_button_attr'] = [
            'class' => $options['dropdown_button_class'] . ' dropdown-toggle',
            'type' => 'button',
            'data-toggle' => 'dropdown',
            'aria-expanded' => 'true',
        ];

        $view->vars['dropdown_menu_attr'] = [
            'class' => $options['dropdown_menu_class'] . ' dropdown-menu',
        ];

        $view->vars['dropdown_items'] = [];

        if (is_callable($options['item_builder'])) {
            $builder = $this->actionListFactory->createBuilder();
            call_user_func($options['item_builder'], $builder, $options);
            $view->vars['dropdown_items'] = $builder->getActionList();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('template', '@UmbrellaCore/Action/dropdown.html.twig')

            ->setDefault('class', 'dropdown ml-1')

            ->setDefault('dropdown_button_class', 'btn btn-light')
            ->setAllowedTypes('dropdown_button_class', 'string')

            ->setDefault('dropdown_menu_class', '')
            ->setAllowedTypes('dropdown_menu_class', 'string')

            ->setDefault('item_builder', null)
            ->setAllowedTypes('item_builder', ['null', 'callable']);
    }
}

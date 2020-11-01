<?php

namespace Umbrella\CoreBundle\Component\Action\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Component\ComponentView;

/**
 * Class DropdownItemDividerActionType
 */
class DropdownItemDividerActionType extends ActionType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(ComponentView $view, array $options)
    {
        $view->vars['attr'] = [
            'class' => $options['class'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('class', 'dropdown-divider')
            ->setDefault('template', '@UmbrellaCore/Action/dropdown_item_divider.html.twig');
    }
}

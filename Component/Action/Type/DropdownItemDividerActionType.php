<?php

namespace Umbrella\CoreBundle\Component\Action\Type;

use Umbrella\CoreBundle\Component\ComponentView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class DropdownItemDividerActionType
 */
class DropdownItemDividerActionType extends ActionType
{
    /**
     * @inheritDoc
     */
    public function buildView(ComponentView $view, array $options)
    {
        $view->vars['attr'] = [
            'class' => $options['class']
        ];
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('class', 'dropdown-divider')
            ->setDefault('template', '@UmbrellaCore/Action/dropdown_item_divider.html.twig');
    }
}

<?php

namespace Umbrella\CoreBundle\Component\Action\Type;

use Umbrella\CoreBundle\Component\ComponentView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class DropdownItemActionType
 */
class DropdownItemActionType extends ActionType
{
    /**
     * @inheritDoc
     */
    public function buildView(ComponentView $view, array $options)
    {
        $view->vars['attr']['class'] = $options['class'] . ' dropdown-item';
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('template', '@UmbrellaCore/Action/dropdown_item.html.twig');
    }
}

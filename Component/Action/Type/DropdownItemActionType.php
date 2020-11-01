<?php

namespace Umbrella\CoreBundle\Component\Action\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Component\ComponentView;

/**
 * Class DropdownItemActionType
 */
class DropdownItemActionType extends ActionType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(ComponentView $view, array $options)
    {
        $view->vars['attr']['class'] = $options['class'] . ' dropdown-item';
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('template', '@UmbrellaCore/Action/dropdown_item.html.twig');
    }
}

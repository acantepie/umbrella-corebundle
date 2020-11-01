<?php

namespace Umbrella\CoreBundle\Component\Action\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Component\ComponentView;

/**
 * Class ActionType
 */
class ActionType
{
    /**
     * @param ComponentView $view
     * @param array         $options
     */
    public function buildView(ComponentView $view, array $options)
    {
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
    }
}

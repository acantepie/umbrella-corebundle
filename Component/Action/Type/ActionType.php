<?php

namespace Umbrella\CoreBundle\Component\Action\Type;

use Umbrella\CoreBundle\Component\ComponentView;
use Symfony\Component\OptionsResolver\OptionsResolver;

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

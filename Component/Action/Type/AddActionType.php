<?php

namespace Umbrella\CoreBundle\Component\Action\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AddActionType
 */
class AddActionType extends ActionType
{
    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('icon', 'mdi mdi-plus mr-1')
            ->setDefault('class', 'btn btn-primary ml-1');
    }
}

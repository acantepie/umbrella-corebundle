<?php

namespace Umbrella\CoreBundle\Component\Widget\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

class DeleteAllLinkType extends LinkType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver
            ->setDefault('xhr', true)
            ->setDefault('confirm', 'message.delete_all_confirm')
            ->setDefault('class', 'btn btn-secondary')
            ->setDefault('icon', 'mdi mdi-delete mr-1');
    }
}
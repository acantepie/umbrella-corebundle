<?php

namespace Umbrella\CoreBundle\Component\DataTable\Adapter;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Component\DataTable\DTO\DataTableRequest;
use Umbrella\CoreBundle\Component\DataTable\DTO\DataTableResult;

class CallableAdapter extends DataTableAdapter
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setRequired('callable')
            ->setAllowedTypes('callable', 'callable');
    }

    public function getResult(DataTableRequest $request, array $options): DataTableResult
    {
        return call_user_func($options['callable'], $request);
    }
}
<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 18/05/17
 * Time: 18:43.
 */

namespace Umbrella\CoreBundle\Component\DataTable;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Component\Toolbar\ToolbarAwareTypeInterface;
use Umbrella\CoreBundle\Component\Toolbar\ToolbarBuilder;

/**
 * Class DataTableType.
 */
class DataTableType implements ToolbarAwareTypeInterface
{
    /**
     * @inheritdoc
     */
    public function buildToolbar(ToolbarBuilder $builder, array $options = array())
    {
    }

    /**
     * @param DataTableBuilder $builder
     * @param array            $options
     */
    public function buildDataTable(DataTableBuilder $builder, array $options = array())
    {
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
    }
}

<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 18/05/17
 * Time: 18:43.
 */

namespace Umbrella\CoreBundle\Component\DataTable\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Component\DataTable\DataTableBuilder;
use Umbrella\CoreBundle\Component\Toolbar\ToolbarBuilder;
use Umbrella\CoreBundle\Component\Toolbar\Type\ToolbarType;

/**
 * Class DataTableType.
 */
class DataTableType extends ToolbarType
{
    /**
     * @param ToolbarBuilder $builder
     * @param array $options
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

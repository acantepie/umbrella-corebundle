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
use Umbrella\CoreBundle\Component\Toolbar\ToolbarAwareTypeInterface;
use Umbrella\CoreBundle\Component\Toolbar\ToolbarBuilder;

/**
 * Class DataTableType.
 */
class DataTableType implements ToolbarAwareTypeInterface
{
    /**
     * @param ToolbarBuilder $builder
     * @param array          $options
     */
    public function buildToolbar(ToolbarBuilder $builder, array $options = [])
    {
    }

    /**
     * @param $builder
     * @param array $options
     */
    public function buildTable(DataTableBuilder $builder, array $options = [])
    {
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
    }
}

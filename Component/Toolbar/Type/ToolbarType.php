<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 13/04/18
 * Time: 20:22
 */

namespace Umbrella\CoreBundle\Component\Toolbar\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Component\Toolbar\ToolbarBuilder;

/**
 * Class ToolbarType
 */
abstract class ToolbarType
{
    /**
     * @param ToolbarBuilder $builder
     * @param array $options
     */
    public function buildToolbar(ToolbarBuilder $builder, array $options)
    {
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
    }

}
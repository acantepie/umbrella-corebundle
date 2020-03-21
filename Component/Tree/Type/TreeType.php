<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 03/06/17
 * Time: 15:03
 */

namespace Umbrella\CoreBundle\Component\Tree\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Component\Tree\TreeBuilder;

/**
 * Class TreeType
 */
abstract class TreeType
{
    /**
     * @param TreeBuilder $builder
     * @param array $options
     */
    public function buildTree(TreeBuilder $builder, array $options)
    {

    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
    }
}
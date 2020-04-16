<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 16/04/20
 * Time: 23:55
 */

namespace Umbrella\CoreBundle\Component\Toolbar\Type;


use Umbrella\CoreBundle\Component\Toolbar\ToolbarBuilder;

/**
 * Interface ToolbarTypeInterface
 * @package Umbrella\CoreBundle\Component\Toolbar\Type
 */
interface ToolbarAwareTypeInterface
{
    /**
     * @param ToolbarBuilder $builder
     * @param array $options
     */
    public function buildToolbar(ToolbarBuilder $builder, array $options);
}
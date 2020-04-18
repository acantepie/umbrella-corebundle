<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 18/04/20
 * Time: 23:35
 */
namespace Umbrella\CoreBundle\Component\Table\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Component\Table\TableBuilder;
use Umbrella\CoreBundle\Component\Toolbar\ToolbarAwareTypeInterface;
use Umbrella\CoreBundle\Component\Toolbar\ToolbarBuilder;

/**
 * Class TableType
 */
abstract class TableType implements ToolbarAwareTypeInterface
{

    /**
     * @param ToolbarBuilder $builder
     * @param array $options
     */
    public function buildToolbar(ToolbarBuilder $builder, array $options = array())
    {

    }

    /**
     * @param $builder
     * @param array $options
     */
    public function buildTable(TableBuilder $builder, array $options = array())
    {

    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {

    }

    /**
     * @return string
     */
    public abstract function componentClass();



}
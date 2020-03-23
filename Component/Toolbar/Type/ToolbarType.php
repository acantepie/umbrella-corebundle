<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 13/04/18
 * Time: 20:22
 */

namespace Umbrella\CoreBundle\Component\Toolbar\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Component\Toolbar\ActionsBuilder;

/**
 * Class ToolbarType
 */
abstract class ToolbarType
{
    /**
     * @param array $options
     * @return null
     */
    public function defaultData(array $options)
    {
        return null;
    }

    /**
     * @param ActionsBuilder $builder
     * @param array $options
     */
    public function buildActions(ActionsBuilder $builder, array $options)
    {
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
    }

}
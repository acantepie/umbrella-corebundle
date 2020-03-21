<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 02/07/17
 * Time: 21:29
 */

namespace Umbrella\CoreBundle\Component\Toolbar\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Component\Toolbar\ActionsBuilder;

/**
 * Class AddToolbar
 */
class AddToolbarType extends ToolbarType
{
    /**
     * @inheritdoc
     */
    public function buildActions(ActionsBuilder $builder, array $options)
    {
        $builder->add('add', AddActionType::class, array(
            'label' => $options['add_label'],
            'route' => $options['add_route'],
            'xhr' => $options['add_xhr'],
            'route_params' => $options['add_route_params']
        ));
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'add_xhr' => true,
            'add_route_params' => array()
        ));

        $resolver->setRequired(array(
            'add_label',
            'add_route'
        ));

        $resolver->setDefault('add_label', 'add');
    }
}
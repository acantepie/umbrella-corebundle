<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 29/06/17
 * Time: 19:38
 */

namespace Umbrella\CoreBundle\Component\Toolbar\Type;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Component\Toolbar\ActionsBuilder;

/**
 * Class DropdownAction
 */
class DropdownActionType extends ActionType
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * DropdownActionType constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefined(array(
            'build_children'
        ));
        $resolver->setAllowedTypes('build_children', 'callable');

        $resolver->setDefault('class', 'btn btn-default');
        $resolver->setDefault('children', array());
        $resolver->setDefault('template', '@UmbrellaCore/Toolbar/Action/action_dropdown.html.twig');

        // hack to build children

        $resolver->setNormalizer('children', function(Options $options, $value) {

            if (!isset($options['build_children']) || !is_callable($options['build_children'])) {
                return [];
            }

            $builder = new ActionsBuilder($this->container);
            call_user_func($options['build_children'], $builder, (array) $options);
            return $builder->getActions();
        });
    }
}
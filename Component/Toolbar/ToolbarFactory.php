<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 23/05/17
 * Time: 20:03.
 */

namespace Umbrella\CoreBundle\Component\Toolbar;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Component\Toolbar\Model\Toolbar;
use Umbrella\CoreBundle\Component\Toolbar\Type\ToolbarType;

/**
 * Class ToolbarFactory.
 */
class ToolbarFactory
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * DataTableFactory constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param $typeClass
     * @param array $options
     *
     * @return Toolbar
     */
    public function create($typeClass, array $options = array())
    {
        $type = $this->createType($typeClass);
        $toolbar = new Toolbar();

        $resolver = new OptionsResolver();
        $toolbar->configureOptions($resolver);
        $type->configureOptions($resolver);
        $resolvedOptions = $resolver->resolve($options);
        $toolbar->setOptions($resolvedOptions);

        // build Actions
        $actionsBuilder = new ActionsBuilder($this->container);
        $type->buildActions($actionsBuilder, $resolvedOptions);
        $toolbar->actions = $actionsBuilder->getActions();

        // build form
        $formFactory = $this->container->get('form.factory');
        $formBuilder = $formFactory->createBuilder(FormType::class, $type->defaultData($resolvedOptions), $resolvedOptions['form_options']);
        $type->buildForm($formBuilder, $resolvedOptions);
        $toolbar->form = $formBuilder->getForm();

        // build queryClosure
        $toolbar->queryClosure = array($type, 'filter');

        return $toolbar;
    }


    /**
     * @param $typeClass
     * @return ToolbarType
     */
    private function createType($typeClass)
    {
        if ($typeClass !== ToolbarType::class && !is_subclass_of($typeClass, ToolbarType::class)) {
            throw new \InvalidArgumentException("Class '$typeClass' must extends ToolbarType class.");
        }

        if ($this->container->has($typeClass)) {
            return $this->container->get($typeClass);
        } else {
            return new $typeClass();
        }
    }
}

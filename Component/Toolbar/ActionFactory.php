<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 16/04/18
 * Time: 19:41
 */

namespace Umbrella\CoreBundle\Component\Toolbar;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Umbrella\CoreBundle\Component\Toolbar\Model\Action;
use Umbrella\CoreBundle\Component\Toolbar\Type\ActionType;

/**
 * Class ActionFactory
 */
class ActionFactory
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * DataTableFactory constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->router = $container->get('router');
    }

    /**
     * @param $typeClass
     * @param array $options
     *
     * @return Action
     */
    public function create($typeClass, array $options = array())
    {
        $type = $this->createType($typeClass);
        $action = new Action($this->router);

        $resolver = new OptionsResolver();
        $action->configureOptions($resolver);
        $type->configureOptions($resolver);
        $resolvedOptions = $resolver->resolve($options);
        $action->setOptions($resolvedOptions);

        return $action;
    }


    /**
     * @param $typeClass
     * @return ActionType
     */
    private function createType($typeClass)
    {
        if ($typeClass !== ActionType::class && !is_subclass_of($typeClass, ActionType::class)) {
            throw new \InvalidArgumentException("Class '$typeClass' must extends ActionType class.");
        }

        if ($this->container->has($typeClass)) {
            return $this->container->get($typeClass);
        } else {
            return new $typeClass();
        }
    }
}
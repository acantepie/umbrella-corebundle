<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 03/06/17
 * Time: 14:16
 */
namespace Umbrella\CoreBundle\Component\Tree;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Component\Tree\Model\Tree;
use Umbrella\CoreBundle\Component\Tree\Type\TreeType;

/**
 * Class TreeFactory
 */
class TreeFactory
{

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * TreeFactory constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param $typeClass
     * @param array $options
     * @return Tree
     */
    public function create($typeClass, array $options = array())
    {
        return $this->createBuilder($typeClass, $options)->getTree();

    }


    /**
     * @param string $typeClass
     * @param array  $options
     *
     * @return TreeBuilder
     */
    public function createBuilder($typeClass, array $options = array())
    {
        $type = $this->createType($typeClass);

        $tree = new Tree($this->container);
        $resolver = new OptionsResolver();
        $tree->configureOptions($resolver);
        $type->configureOptions($resolver);
        $options = $resolver->resolve($options);

        $builder = new TreeBuilder($this->container, $options);
        $type->buildTree($builder, $options);

        return $builder;
    }


    /**
     * @param $typeClass
     *
     * @return TreeType
     */
    protected function createType($typeClass)
    {
        if ($typeClass !== TreeType::class && !is_subclass_of($typeClass, TreeType::class)) {
            throw new \InvalidArgumentException("Class '$typeClass' must extends TreeType class.");
        }

        if ($this->container->has($typeClass)) {
            return $this->container->get($typeClass);
        } else {
            return new $typeClass();
        }
    }

}

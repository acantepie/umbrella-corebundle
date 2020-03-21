<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 18/05/17
 * Time: 18:51.
 */

namespace Umbrella\CoreBundle\Component\DataTable;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Component\DataTable\Model\DataTable;
use Umbrella\CoreBundle\Component\DataTable\Type\DataTableType;

/**
 * Class DataTableFactory.
 */
class DataTableFactory
{
    /**
     * @var ContainerInterface
     */
    private $container;

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
     * @return DataTable
     */
    public function create($typeClass, array $options = array())
    {
        return $this->createBuilder($typeClass, $options)->getTable();
    }

    /**
     * @param string $typeClass
     * @param array  $options
     *
     * @return DataTableBuilder
     */
    public function createBuilder($typeClass = DataTableType::class, array $options = array())
    {
        $type = $this->createType($typeClass);

        $dt = new DataTable();
        $resolver = new OptionsResolver();
        $dt->configureOptions($resolver);
        $type->configureOptions($resolver);
        $options = $resolver->resolve($options);

        $builder = new DataTableBuilder($this->container, $options);
        $type->buildDataTable($builder, $options);

        return $builder;
    }

    /**
     * @param $typeClass
     *
     * @return DataTableType
     */
    private function createType($typeClass)
    {
        if ($typeClass !== DataTableType::class && !is_subclass_of($typeClass, DataTableType::class)) {
            throw new \InvalidArgumentException("Class '$typeClass' must extends DataTableType class.");
        }

        if ($this->container->has($typeClass)) {
            return $this->container->get($typeClass);
        } else {
            return new $typeClass();
        }
    }
}

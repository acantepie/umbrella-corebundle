<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 18/05/17
 * Time: 18:51.
 */

namespace Umbrella\CoreBundle\Component\DataTable;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Umbrella\CoreBundle\Component\DataTable\Model\DataTable;
use Umbrella\CoreBundle\Component\DataTable\Type\DataTableType;
use Umbrella\CoreBundle\Component\Toolbar\ToolbarFactory;

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
     * TODO remove container DI => use registry
     *
     * DataTableFactory constructor.
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
        $builder = new DataTableBuilder(
            $this->container->get('doctrine')->getManager(),
            $this->container->get('router'),
            $this->container->get(ToolbarFactory::class),
            $this->container->get(ColumnFactory::class),
            $this->createType($typeClass),
            $options
        );

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

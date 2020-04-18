<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 18/05/17
 * Time: 18:51.
 */

namespace Umbrella\CoreBundle\Component\DataTable;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\RouterInterface;
use Umbrella\CoreBundle\Component\Toolbar\ToolbarFactory;

/**
 * Class DataTableFactory.
 */
class DataTableFactory
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var ToolbarFactory
     */
    private $toolbarFactory;

    /**
     * @var ColumnFactory
     */
    private $columnFactory;

    /**
     * @var DataTableType[]
     */
    private $dataTableTypes = array();

    /**
     * DataTableFactory constructor.
     * @param EntityManagerInterface $em
     * @param RouterInterface $router
     * @param ToolbarFactory $toolbarFactory
     * @param ColumnFactory $columnFactory
     */
    public function __construct(EntityManagerInterface $em, RouterInterface $router, ToolbarFactory $toolbarFactory, ColumnFactory $columnFactory)
    {
        $this->em = $em;
        $this->router = $router;
        $this->toolbarFactory = $toolbarFactory;
        $this->columnFactory = $columnFactory;
    }

    /**
     * @param $id
     * @param DataTableType $dataTableType
     */
    public function registerDataTableType($id, DataTableType $dataTableType)
    {
        $this->dataTableTypes[$id] = $dataTableType;
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
        return new DataTableBuilder($this->em, $this->router, $this->toolbarFactory, $this->columnFactory, $this->createType($typeClass), $options);
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

        if (array_key_exists($typeClass, $this->dataTableTypes)) {
            return $this->dataTableTypes[$typeClass];
        } else {
            return new $typeClass();
        }
    }
}

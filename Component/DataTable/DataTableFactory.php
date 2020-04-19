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
use Umbrella\CoreBundle\Component\DataTable\Type\DataTableType;
use Umbrella\CoreBundle\Component\Column\ColumnFactory;
use Umbrella\CoreBundle\Component\DataTable\Model\AbstractDataTable;
use Umbrella\CoreBundle\Component\Toolbar\ToolbarFactory;

/**
 * Class TableFactory.
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
     * TableFactory constructor.
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
     * @return AbstractDataTable
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
            throw new \InvalidArgumentException(sprintf("Class '%s' must extends %s class.", $typeClass, DataTableType::class));
        }

        if (array_key_exists($typeClass, $this->dataTableTypes)) {
            return $this->dataTableTypes[$typeClass];
        } else {
            return new $typeClass();
        }
    }
}

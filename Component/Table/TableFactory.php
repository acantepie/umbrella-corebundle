<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 18/05/17
 * Time: 18:51.
 */

namespace Umbrella\CoreBundle\Component\Table;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\RouterInterface;
use Umbrella\CoreBundle\Component\Table\Type\TableType;
use Umbrella\CoreBundle\Component\Column\ColumnFactory;
use Umbrella\CoreBundle\Component\Table\Model\Table;
use Umbrella\CoreBundle\Component\Toolbar\ToolbarFactory;

/**
 * Class TableFactory.
 */
class TableFactory
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
     * @var TableType[]
     */
    private $tableTypes = array();

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
     * @param TableType $dataTableType
     */
    public function registerTableType($id, TableType $dataTableType)
    {
        $this->tableTypes[$id] = $dataTableType;
    }

    /**
     * @param $typeClass
     * @param array $options
     *
     * @return Table
     */
    public function create($typeClass, array $options = array())
    {
        return $this->createBuilder($typeClass, $options)->getTable();
    }

    /**
     * @param string $typeClass
     * @param array  $options
     *
     * @return TableBuilder
     */
    public function createBuilder($typeClass = TableType::class, array $options = array())
    {
        return new TableBuilder($this->em, $this->router, $this->toolbarFactory, $this->columnFactory, $this->createType($typeClass), $options);
    }

    /**
     * @param $typeClass
     *
     * @return TableType
     */
    private function createType($typeClass)
    {
        if ($typeClass !== TableType::class && !is_subclass_of($typeClass, TableType::class)) {
            throw new \InvalidArgumentException(sprintf("Class '%s' must extends %s class.", $typeClass, TableType::class));
        }

        if (array_key_exists($typeClass, $this->tableTypes)) {
            return $this->tableTypes[$typeClass];
        } else {
            return new $typeClass();
        }
    }
}

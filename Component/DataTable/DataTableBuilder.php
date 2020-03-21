<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 18/05/17
 * Time: 18:55.
 */

namespace Umbrella\CoreBundle\Component\DataTable;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\RouterInterface;
use Umbrella\CoreBundle\Component\DataTable\Model\Column;
use Umbrella\CoreBundle\Component\DataTable\Model\DataTable;
use Umbrella\CoreBundle\Component\DataTable\Model\DataTableQuery;
use Umbrella\CoreBundle\Component\DataTable\Model\DataTableQueryInterface;
use Umbrella\CoreBundle\Component\DataTable\Type\ColumnType;
use Umbrella\CoreBundle\Component\DataTable\Type\PropertyColumnType;
use Umbrella\CoreBundle\Component\DataTable\Type\RelocateColumnType;
use Umbrella\CoreBundle\Component\Toolbar\ToolbarFactory;

/**
 * Class DataTableBuilder.
 */
class DataTableBuilder
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
     * @var ToolbarFactory
     */
    private $toolbarFactory;

    /**
     * @var ColumnFactory
     */
    private $columnFactory;

    /**
     * @var array
     */
    private $options = array();

    /**
     * @var string
     */
    private $rowUrl;

    /**
     * @var boolean
     */
    private $rowXhr;

    /**
     * @var boolean
     */
    private $rowTargetBlank;

    /**
     * @var boolean
     */
    private $rowXhrSpinner;

    /**
     * @var string
     */
    private $loadUrl;

    /**
     * @var boolean
     */
    private $relocateUrl;

    /**
     * @var \Closure
     */
    private $queryClosure;

    /**
     * @var DataTableQueryInterface
     */
    private $queryHandler;

    /**
     * @var string
     */
    private $toolbarClass;

    /**
     * @var array
     */
    private $toolbarOptions = array();

    /**
     * @var array
     */
    private $columns = array();

    /**
     * DataTableBuilder constructor.
     *
     * @param ContainerInterface $container
     * @param array $options
     */
    public function __construct(ContainerInterface $container, array $options = array())
    {
        $this->container = $container;
        $this->router = $container->get('router');
        $this->toolbarFactory = $container->get(ToolbarFactory::class);
        $this->columnFactory = $container->get(ColumnFactory::class);
        $this->options = $options;
    }

    /**
     * @param $id
     * @param $columnClass
     * @param array $options
     *
     * @return $this
     */
    public function add($id, $columnClass = PropertyColumnType::class, array $options = array())
    {
        $this->columns[$id] = array(
            'class' => $columnClass,
            'options' => $options,
        );

        return $this;
    }

    /**
     * @param $beforeId
     * @param $id
     * @param $columnClass
     * @param array $options
     * @return $this
     * @throws \Exception
     */
    public function addBefore($beforeId, $id, $columnClass, array $options = array())
    {
        $idx = array_search($beforeId, array_keys($this->columns));
        if (false === $idx) {
            throw new \Exception(sprintf('The column with id "%s" does not exist.', $beforeId));
        }

        $newCols = array(
            $id => array(
                'class' => $columnClass,
                'options' => $options,
            )
        );

        if ($idx > 0) {
            $this->columns = array_slice($this->columns, 0, $idx, true)
                + $newCols
                + array_slice($this->columns, $idx, count($this->columns) - $idx, true);
        } else {
            $this->columns = $newCols + $this->columns;
        }

        return $this;
    }

    /**
     * @param $afterId
     * @param $id
     * @param $columnClass
     * @param array $options
     * @return $this
     * @throws \Exception
     */
    public function addAfter($afterId, $id, $columnClass, array $options = array())
    {
        $idx = array_search($afterId, array_keys($this->columns));
        if (false === $idx) {
            throw new \Exception(sprintf('The column with id "%s" does not exist.', $afterId));
        }

        $newCols = array(
            $id => array(
                'class' => $columnClass,
                'options' => $options,
            )
        );

        if ($idx < count($this->columns) - 1) {
            $this->columns = array_slice($this->columns, 0, $idx + 1, true)
                + $newCols
                + array_slice($this->columns, $idx + 1, count($this->columns) - ($idx + 1), true);
        } else {
            $this->columns = $this->columns + $newCols;
        }

        return $this;
    }


    public function remove($id)
    {
        unset($this->columns[$id]);
        return $this;
    }

    /**
     * @param $id
     *
     * @return bool
     */
    public function has($id)
    {
        return isset($this->columns[$id]);
    }

    /**
     * @param $id
     * @return Column
     * @throws \Exception
     */
    public function get($id)
    {
        if (isset($this->columns[$id]['resolved'])) {
            return $this->columns[$id]['resolved'];
        }

        if (isset($this->columns[$id])) {
            $this->resolveColumn($id);
            return $this->columns[$id]['resolved'];
        }

        throw new \Exception(sprintf('The column with id "%s" does not exist.', $id));

    }

    /**
     * @param $route
     * @param array $params
     */
    public function setLoadAction($route, array $params = array())
    {
        $this->loadUrl = $this->router->generate($route, $params);
    }

    /**
     * @param $route
     * @param array $params
     * @param bool $xhr
     * @param bool $spinner
     * @param bool $targetBlank
     */
    public function setRowAction($route, array $params = array(), $xhr = true, $spinner = false, $targetBlank = false)
    {
        // hack to replace id on js
        if (!isset($params['id'])) {
            $params['id'] = 123456789;
        }
        $this->rowUrl = $this->router->generate($route, $params);
        $this->rowXhr = $xhr;
        $this->rowXhrSpinner = $spinner;
        $this->rowTargetBlank = $targetBlank;
    }

    /**
     * @param $route
     * @param array $params
     */
    public function setRelocateAction($route, array $params = array())
    {
        $this->relocateUrl = $this->router->generate($route, $params);
    }

    /**
     * @param callable $queryClosure
     */
    public function setQuery(callable $queryClosure)
    {
        $this->queryClosure = $queryClosure;
    }

    /**
     * @param DataTableQueryInterface
     */
    public function setQueryHandler(DataTableQueryInterface $queryHandler)
    {
        $this->queryHandler = $queryHandler;
    }

    /**
     * @param $queryClass
     * @deprecated Use setQueryHandler method instead
     */
    public function setQueryClass($class)
    {
        if ($class !== DataTableQueryInterface::class && !is_subclass_of($class, DataTableQueryInterface::class)) {
            throw new \InvalidArgumentException("Class '$class' must extends DataTableQueryInterface class.");
        }

        if ($this->container->has($class)) {
            $this->setQueryHandler($this->container->get($class));
        } else {
            $this->setQueryHandler(new $class());
        }
    }

    /**
     * @param $class
     * @param array $options
     */
    public function setToolbar($class, array $options = array())
    {
        $this->toolbarClass = $class;
        $this->toolbarOptions = $options;
    }

    /**
     * @return DataTable
     */
    public function getTable()
    {
        $this->resolveColumns();

        $table = new DataTable();
        foreach ($this->columns as $arg) {
            $table->columns[] = $arg['resolved'];
        }
        $table->setOptions($this->options);

        $table->loadUrl = $this->loadUrl;
        $table->rowUrl = $this->rowUrl;
        $table->rowXhr = $this->rowXhr;
        $table->rowTargetBlank = $this->rowTargetBlank;
        $table->rowXhrSpinner = $this->rowXhrSpinner;

        $table->queryClosure = $this->queryClosure;

        if ($this->toolbarClass) {
            $table->toolbar = $this->toolbarFactory->create($this->toolbarClass, $this->toolbarOptions);
        }

        $table->query = null === $this->queryHandler
            ? new DataTableQuery($this->container->get('doctrine.orm.entity_manager'))
            : $this->queryHandler;

        // override some default options
        if ($this->relocateUrl) {
            $table->relocateUrl = $this->relocateUrl;
//            $table->orderable = false; // disable sort on columns
//            $table->paging = false; // disable paging
        }

        return $table;
    }

    /**
     *
     */
    protected function resolveColumns()
    {
        foreach ($this->columns as $id => $column) {
            if (!isset($column['resolved'])) {
                $this->resolveColumn($id);
            }
        }
    }

    /**
     * @param $id
     */
    protected function resolveColumn($id)
    {
        $column = $this->columns[$id];
        $column['options']['id'] = $id;
        $this->columns[$id]['resolved'] = $this->columnFactory->create($column['class'], $column['options']);
    }

}

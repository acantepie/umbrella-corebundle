<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 18/05/17
 * Time: 18:55.
 */

namespace Umbrella\CoreBundle\Component\DataTable;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Umbrella\CoreBundle\Component\Column\Column;
use Umbrella\CoreBundle\Component\Column\PropertyColumnType;
use Umbrella\CoreBundle\Component\DataTable\Source\AbstractDataTableSource;
use Umbrella\CoreBundle\Component\DataTable\Source\EntityDataTableSource;
use Umbrella\CoreBundle\Component\Source\AbstractSourceModifier;
use Umbrella\CoreBundle\Component\Toolbar\ToolbarFactory;

/**
 * Class DataTableBuilder.
 */
class DataTableBuilder
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
     * @var DataTableType
     */
    private $type;

    /**
     * @var array
     */
    private $options = array();

    /**
     * @var string
     */
    private $loadUrl;

    /**
     * @var boolean
     */
    private $relocateUrl;

    /**
     * @var array
     */
    private $columns = array();

    /**
     * @var AbstractDataTableSource
     */
    private $source;

    /**
     * @var AbstractSourceModifier[]
     */
    private $sourceModifiers = array();

    /**
     * DataTableBuilder constructor.
     *
     * @param EntityManagerInterface $em
     * @param RouterInterface $router
     * @param ToolbarFactory $toolbarFactory
     * @param ColumnFactory $columnFactory
     * @param $type
     * @param array $options
     */
    public function __construct(
        EntityManagerInterface $em,
        RouterInterface $router,
        ToolbarFactory $toolbarFactory,
        ColumnFactory $columnFactory,
        $type,
        array $options = array()
    )
    {
        $this->em = $em;
        $this->router = $router;
        $this->toolbarFactory = $toolbarFactory;
        $this->columnFactory = $columnFactory;

        $this->type = $type;

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
     */
    public function setRelocateAction($route, array $params = array())
    {
        $this->relocateUrl = $this->router->generate($route, $params);
    }

    /**
     * @param AbstractDataTableSource $source
     */
    public function setSource(AbstractDataTableSource $source)
    {
        $this->source = $source;
    }

    /**
     * @param $entityClass
     */
    public function setEntitySource($entityClass)
    {
        $this->source = new EntityDataTableSource($this->em, $entityClass);
    }

    /**
     * @param AbstractSourceModifier $modifier
     */
    public function addSourceModifier(AbstractSourceModifier $modifier)
    {
        $this->sourceModifiers[] = $modifier;
    }

    /**
     *
     */
    public function clearSourceModifiers()
    {
        $this->sourceModifiers = [];
    }

    /**
     * @return DataTable
     */
    public function getTable()
    {
        $table = new DataTable();
        $table->setInnerType($this->type);

        // resolve options
        $resolver = new OptionsResolver();
        $table->configureOptions($resolver);
        $this->type->configureOptions($resolver);
        $resolvedOptions = $resolver->resolve($this->options);

        $this->type->buildDataTable($this, $resolvedOptions);
        $table->setOptions($resolvedOptions);

        // resolve toolbar
        $table->toolbar = $this->toolbarFactory->create($this->type, $resolvedOptions);

        // resolve columns
        $this->resolveColumns();
        foreach ($this->columns as $arg) {
            $table->columns[] = $arg['resolved'];
        }

        // resolve source
        $table->source = $this->source ? $this->source : $this->getDefaultSource($table);
        if (null === $table->source) {
            throw new \RuntimeException("No source configured for datatable, call setSource() to configure one");
        }

        // resolve source modifiers
        $table->source->setModifiers($this->sourceModifiers);

        // resolve urls
        $table->loadUrl = $this->loadUrl;
        $table->relocateUrl = $this->relocateUrl;

        return $table;
    }

    /**
     * @param DataTable $table
     * @return null|EntityDataTableSource
     */
    protected function getDefaultSource(DataTable $table)
    {
        return !empty($table->getDataClass()) ? new EntityDataTableSource($this->em, $table->getDataClass()) : null;
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

<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 18/05/17
 * Time: 18:55.
 */

namespace Umbrella\CoreBundle\Component\DataTable;

use Doctrine\ORM\EntityManagerInterface;
use Umbrella\CoreBundle\Utils\ComponentUtils;
use Symfony\Component\Routing\RouterInterface;
use Umbrella\CoreBundle\Component\Column\Column;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Component\Column\ColumnFactory;
use Umbrella\CoreBundle\Component\Column\Type\ColumnType;
use Umbrella\CoreBundle\Component\Toolbar\ToolbarFactory;
use Umbrella\CoreBundle\Component\DataTable\Model\DataTable;
use Umbrella\CoreBundle\Component\DataTable\Type\DataTableType;
use Umbrella\CoreBundle\Component\DataTable\Model\AbstractDataTable;
use Umbrella\CoreBundle\Component\DataTable\Source\AbstractTableSource;
use Umbrella\CoreBundle\Component\DataTable\Source\EntityDataTableSource;
use Umbrella\CoreBundle\Component\DataTable\Source\EntityTreeTableSource;
use Umbrella\CoreBundle\Component\DataTable\Source\AbstractSourceModifier;

/**
 * Class TableBuilder.
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
    private $options = [];

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
    private $columns = [];

    /**
     * @var AbstractTableSource
     */
    private $source;

    /**
     * @var AbstractSourceModifier[]
     */
    private $sourceModifiers = [];

    /**
     * TableBuilder constructor.
     *
     * @param EntityManagerInterface $em
     * @param RouterInterface        $router
     * @param ToolbarFactory         $toolbarFactory
     * @param ColumnFactory          $columnFactory
     * @param $type
     * @param array $options
     */
    public function __construct(
        EntityManagerInterface $em,
        RouterInterface $router,
        ToolbarFactory $toolbarFactory,
        ColumnFactory $columnFactory,
        $type,
        array $options = []
    ) {
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
    public function add($id, $columnClass = ColumnType::class, array $options = [])
    {
        $this->columns[$id] = [
            'class' => $columnClass,
            'options' => $options,
        ];

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
    public function setLoadAction($route, array $params = [])
    {
        $this->loadUrl = $this->router->generate($route, $params);
    }

    /**
     * @param $route
     * @param array $params
     */
    public function setRelocateAction($route, array $params = [])
    {
        $this->relocateUrl = $this->router->generate($route, $params);
    }

    /**
     * @param AbstractTableSource $source
     */
    public function setSource(AbstractTableSource $source)
    {
        $this->source = $source;
    }

    /**
     * @param AbstractSourceModifier $modifier
     */
    public function addSourceModifier(AbstractSourceModifier $modifier)
    {
        $this->sourceModifiers[] = $modifier;
    }

    public function clearSourceModifiers()
    {
        $this->sourceModifiers = [];
    }

    /**
     * @return AbstractDataTable
     */
    public function getTable()
    {
        $table = new DataTable(ComponentUtils::typeClassToId(get_class($this->type)));

        // resolve options
        $resolver = new OptionsResolver();
        $table->configureOptions($resolver);
        $this->type->configureOptions($resolver);
        $resolvedOptions = $resolver->resolve($this->options);

        $this->type->buildTable($this, $resolvedOptions);
        $table->setOptions($resolvedOptions);

        // resolve toolbar
        $table->setToolbar($this->toolbarFactory->create($this->type, $resolvedOptions));

        // resolve columns
        $this->resolveColumns();
        foreach ($this->columns as $arg) {
            $table->addColumn($arg['resolved']);
        }

        // resolve source
        if ($this->source === null) {
            $source = $resolvedOptions['tree']
                ? new EntityTreeTableSource($this->em) // default source for tree data
                : new EntityDataTableSource($this->em); // default source for regular data
        } else {
            $source = $this->source;
        }
        $source->setModifiers($this->sourceModifiers);
        $table->setSource($source);

        // resolve urls
        $table->setLoadUrl($this->loadUrl);
        $table->setRelocateUrl($this->relocateUrl);

        return $table;
    }

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

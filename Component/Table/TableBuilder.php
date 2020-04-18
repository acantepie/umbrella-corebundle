<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 18/05/17
 * Time: 18:55.
 */

namespace Umbrella\CoreBundle\Component\Table;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Umbrella\CoreBundle\Component\Table\Type\TableType;
use Umbrella\CoreBundle\Component\Column\Column;
use Umbrella\CoreBundle\Component\Column\ColumnFactory;
use Umbrella\CoreBundle\Component\Column\PropertyColumnType;
use Umbrella\CoreBundle\Component\Table\Model\Table;
use Umbrella\CoreBundle\Component\Table\Source\AbstractSourceModifier;
use Umbrella\CoreBundle\Component\Table\Source\AbstractTableSource;
use Umbrella\CoreBundle\Component\Table\Source\EntityDataTableSource;
use Umbrella\CoreBundle\Component\Toolbar\ToolbarFactory;
use Umbrella\CoreBundle\Utils\ComponentUtils;

/**
 * Class TableBuilder.
 */
class TableBuilder
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
     * @var TableType
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
     * @var AbstractTableSource
     */
    private $source;

    /**
     * @var AbstractSourceModifier[]
     */
    private $sourceModifiers = array();

    /**
     * TableBuilder constructor.
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

    /**
     *
     */
    public function clearSourceModifiers()
    {
        $this->sourceModifiers = [];
    }

    /**
     * @return Table
     */
    public function getTable()
    {
        $componentClass = $this->type->componentClass();
        if (!is_subclass_of($componentClass,  Table::class)) {
            throw new \RuntimeException(sprintf('Invalid class %s, component must extends Table class', $componentClass));
        }

        /** @var Table $table */
        $table = new $componentClass(ComponentUtils::typeClassToId(get_class($this->type)));

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
        $source = $this->source ? $this->source : new EntityDataTableSource($this->em);
        $source->setModifiers($this->sourceModifiers);
        $table->setSource($source);

        // resolve urls
        $table->setLoadUrl($this->loadUrl);
        $table->setRelocateUrl($this->relocateUrl);

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

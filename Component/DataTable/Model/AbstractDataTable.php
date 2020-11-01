<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 18/04/20
 * Time: 23:50
 */

namespace Umbrella\CoreBundle\Component\DataTable\Model;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Umbrella\CoreBundle\Component\Column\Column;
use Umbrella\CoreBundle\Component\ComponentView;
use Umbrella\CoreBundle\Component\DataTable\Source\AbstractTableSource;
use Umbrella\CoreBundle\Component\DataTable\Type\DataTableType;
use Umbrella\CoreBundle\Component\Toolbar\Toolbar;

/**
 * Class Table
 */
abstract class AbstractDataTable
{
    /**
     * @var DataTableType
     */
    protected $type;

    /**
     * @var string
     */
    protected $defaultId;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var Toolbar
     */
    protected $toolbar;

    /**
     * @var Column[]
     */
    protected $columns = [];

    /**
     * @var AbstractTableSource
     */
    protected $source;

    /**
     * @var string
     */
    protected $loadUrl = '';

    /**
     * @var string
     */
    protected $relocateUrl;

    /**
     * @var bool
     */
    protected $isCallback = false;

    /**
     * Table constructor.
     *
     * @param string $defaultId
     */
    final public function __construct($defaultId = null)
    {
        $this->defaultId = $defaultId;
    }

    /**
     * @param DataTableType $type
     */
    public function setType(DataTableType $type)
    {
        $this->type = $type;
    }

    /**
     * @param Toolbar $toolbar
     */
    public function setToolbar($toolbar)
    {
        $this->toolbar = $toolbar;
    }

    /**
     * @return Toolbar
     */
    public function getToolbar()
    {
        return $this->toolbar;
    }

    /**
     * @param Column $column
     */
    public function addColumn(Column $column)
    {
        $this->columns[] = $column;
    }

    /**
     * @return Column[]
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @return AbstractTableSource
     */
    public function getSource(): AbstractTableSource
    {
        return $this->source;
    }

    /**
     * @param AbstractTableSource $source
     */
    public function setSource($source)
    {
        $this->source = $source;
    }

    /**
     * @param string $loadUrl
     */
    public function setLoadUrl($loadUrl)
    {
        $this->loadUrl = $loadUrl;
    }

    /**
     * @param string $relocateUrl
     */
    public function setRelocateUrl($relocateUrl)
    {
        $this->relocateUrl = $relocateUrl;
    }

    /**
     * @param Request $request
     */
    abstract public function handleRequest(Request $request);

    /**
     * Hack to handle submitted data when request is not available
     *
     * @param array $requestData
     */
    abstract public function handleRequestData(array $requestData);

    /**
     * You must call isCallback() method before call one
     * Return json serialisable data for api
     *
     * @return \JsonSerializable|array
     */
    abstract public function getApiResults();

    /**
     * You must call isCallback() method before call this one
     *
     * @return DataTableResult
     */
    abstract public function getResults(): DataTableResult;

    /**
     * @param OptionsResolver $resolver
     *
     * @return mixed
     */
    abstract public function configureOptions(OptionsResolver $resolver);

    /**
     * @param array $options
     */
    public function setOptions(array $options = [])
    {
        $this->options = $options;
    }

    /**
     * @return bool
     */
    public function isCallback()
    {
        return $this->isCallback;
    }

    /**
     * @param TranslatorInterface $translator
     *
     * @return ComponentView
     */
    abstract public function createView(TranslatorInterface $translator): ComponentView;
}

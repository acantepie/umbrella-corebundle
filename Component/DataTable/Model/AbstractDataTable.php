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
use Umbrella\CoreBundle\Component\DataTable\Source\AbstractTableSource;
use Umbrella\CoreBundle\Component\Toolbar\Toolbar;
use Umbrella\CoreBundle\Model\OptionsAwareInterface;

/**
 * Class Table
 */
abstract class AbstractDataTable implements OptionsAwareInterface
{
    /**
     * @var string
     */
    protected $defaultId;

    /**
     * @var array
     */
    protected $options = array();

    /**
     * @var Toolbar
     */
    protected $toolbar;

    /**
     * @var Column[]
     */
    protected $columns = array();

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
     * @param string $defaultId
     */
    public final function __construct($defaultId = null)
    {
        $this->defaultId = $defaultId;
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
    public abstract function handleRequest(Request $request);

    /**
     * @return \JsonSerializable|array
     */
    public abstract function getApiResults();

    /**
     * @param OptionsResolver $resolver
     * @return mixed
     */
    public abstract function configureOptions(OptionsResolver $resolver);

    /**
     * @param array $options
     */
    public function setOptions(array $options = array())
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
     * @return string
     */
    public function getTemplate()
    {
        return $this->options['template'];
    }

    /**
     * @param TranslatorInterface $translator
     * @return array
     */
    public function getViewOptions(TranslatorInterface $translator)
    {
        return $this->options;
    }

}
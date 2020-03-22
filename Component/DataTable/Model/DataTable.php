<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 13/05/17
 * Time: 12:46.
 */

namespace Umbrella\CoreBundle\Component\DataTable\Model;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Umbrella\CoreBundle\Model\OptionsAwareInterface;
use Umbrella\CoreBundle\Component\Toolbar\Model\Toolbar;
use Umbrella\CoreBundle\Utils\ArrayUtils;

/**
 * Class DataTable.
 */
class DataTable implements OptionsAwareInterface
{
    /**
     * @var string
     */
    public $id;

    // Options

    /**
     * @var string
     */
    public $translationPrefix = 'table.';

    /**
     * @var string
     */
    public $containerClass;

    /**
     * @var string
     */
    public $class;

    /**
     * @var string|callable
     */
    public $rowClass;

    /**
     * @var bool
     */
    public $paging;

    /**
     * @var bool
     */
    public $info;

    /**
     * @var array
     */
    public $lengthMenu;

    /**
     * @var int
     */
    public $pageLength;

    /**
     * @var bool
     */
    public $lengthChange;

    /**
     * @var bool
     */
    public $fixedHeader;

    /**
     * @var string
     */
    public $template;

    /**
     * @var string
     */
    public $loadUrl;

    /**
     * @var string
     */
    public $rowUrl;

    /**
     * @var string
     */
    public $relocateUrl;

    /**
     * @var bool
     */
    public $rowXhr = true;

    /**
     * @var bool
     */
    public $rowXhrSpinner = false;

    /**
     * @var bool
     */
    public $rowTargetBlank = false;

    /**
     * @var int (s)
     */
    public $pollInterval;

    /**
     * @var string
     */
    public $entityName;

    /**
     * @var \Closure|null
     */
    public $queryClosure;

    /**
     * @var array
     */
    public $columns = array();

    /**
     * @var Toolbar
     */
    public $toolbar;

    /**
     * @var bool
     */
    public $orderable;

    /**
     * @var string
     */
    public $dom;

    // Model

    /**
     * @var DataTableQueryInterface
     */
    public $query;

    /**
     * @var int
     */
    private $draw;


    /**
     * @var Request
     */
    private $requestToHandle = null;


    /**
     * @param Request $request
     */
    public function handleRequest(Request $request)
    {
        $this->draw = $request->get('draw');
        $this->requestToHandle = $request;
    }

    /**
     *
     */
    public function buildQuery()
    {
        $this->query->build($this);
        if ($this->requestToHandle !== null) {
            $this->query->handleRequest($this->requestToHandle, $this);
        }
    }

    /**
     * @return Paginator
     */
    public function getResults()
    {
        $this->buildQuery();
        return $this->query->getResults();
    }


    /**
     * @return array
     */
    public function getApiResults()
    {
        $fetchedResults = array();
        $results = $this->getResults();

        foreach ($results['data'] as $row) {
            $fetchedRow = array();

            // Add row id data
            $fetchedRow['DT_RowId'] = PropertyAccess::createPropertyAccessor()->getValue($row, 'id');
            // Add row class data
            if (is_string($this->rowClass)) {
                $fetchedRow['DT_RowClass'] = $this->rowClass;
            } else if (is_callable($this->rowClass)) {
                $fetchedRow['DT_RowClass'] = call_user_func($this->rowClass, $row);
            }

            foreach ($this->columns as $column) {
                $fetchedRow[] = $column->render($row);
            }

            $fetchedResults[] = $fetchedRow;
        }

        return array(
            'draw' => $this->draw,
            'recordsTotal' => $results['recordsTotal'], // Total records, before filtering
            'recordsFiltered' => $results['recordsFiltered'], // Total records, after filtering
            'data' => $fetchedResults,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(array(
            'entity',
        ));

        $resolver->setDefined(array(
            'id',

            'entity',

            'class',
            'row_class',
            'template',
            'paging',
            'length_change',
            'length_menu',
            'page_length',
            'fixed_header',
            'toolbar',
            'poll_interval',
            'orderable',

            'dom' // Datatable.js HTML DOM
        ));


        $resolver->setAllowedTypes('row_class', ['string', 'callable']);
        $resolver->setAllowedTypes('paging', 'bool');
        $resolver->setAllowedTypes('length_change', 'bool');
        $resolver->setAllowedTypes('length_menu', 'array');
        $resolver->setAllowedTypes('page_length', 'int');
        $resolver->setAllowedTypes('fixed_header', 'bool');
        $resolver->setAllowedTypes('toolbar', [Toolbar::class, 'null']);
        $resolver->setAllowedTypes('poll_interval', ['int', 'null']);
        $resolver->setAllowedTypes('dom', 'string');

        $resolver->setDefault('container_class', '');
        $resolver->setDefault('class', 'table-striped table-centered');
        $resolver->setDefault('row_class', '');
        $resolver->setDefault('template', '@UmbrellaCore/DataTable/datatable.html.twig');
        $resolver->setDefault('paging', true);
        $resolver->setDefault('info', true);
        $resolver->setDefault('length_change', false);
        $resolver->setDefault('length_menu', array(25, 50, 100));
        $resolver->setDefault('page_length', 25);
        $resolver->setDefault('fixed_header', false);
        $resolver->setDefault('orderable', true);
        $resolver->setDefault('dom',  "<'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'li><'col-sm-12 col-md-7'p>>");
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options = array())
    {
        $this->id = ArrayUtils::get($options, 'id', 'table_'.substr(md5(uniqid('', true)), 0, 12));
        $this->containerClass = ArrayUtils::get($options, 'container_class');
        $this->class = ArrayUtils::get($options, 'class');
        $this->rowClass = ArrayUtils::get($options, 'row_class');
        $this->template = ArrayUtils::get($options, 'template');

        $this->entityName = ArrayUtils::get($options, 'entity');

        $this->paging = ArrayUtils::get($options, 'paging');
        $this->info = ArrayUtils::get($options, 'info');
        $this->lengthChange = ArrayUtils::get($options, 'length_change');
        $this->lengthMenu = ArrayUtils::get($options, 'length_menu');
        $this->pageLength = ArrayUtils::get($options, 'page_length');

        $this->fixedHeader = ArrayUtils::get($options, 'fixed_header');
        $this->orderable = ArrayUtils::get($options, 'orderable');
        $this->toolbar = ArrayUtils::get($options, 'toolbar');
        $this->pollInterval = ArrayUtils::get($options, 'poll_interval');
        $this->dom = ArrayUtils::get($options, 'dom');
    }

}

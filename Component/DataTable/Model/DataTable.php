<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 13/05/17
 * Time: 12:46.
 */

namespace Umbrella\CoreBundle\Component\DataTable\Model;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Contracts\Translation\TranslatorInterface;
use Umbrella\CoreBundle\Model\OptionsAwareInterface;
use Umbrella\CoreBundle\Component\Toolbar\Model\Toolbar;
use Umbrella\CoreBundle\Utils\StringUtils;

/**
 * Class DataTable.
 */
class DataTable implements OptionsAwareInterface
{
    /**
     * @var array
     */
    private $options = array();

    // URLS

    /**
     * @var string
     */
    public $loadUrl;

    /**
     * @var string
     */
    public $relocateUrl;

    // MODEL

    /**
     * @var Toolbar
     */
    public $toolbar;

    /**
     * @var Column[]
     */
    public $columns = array();

    /**
     * @var AbstractDataTableSource
     */
    public $source;

    // OTHER

    /**
     * @var array()
     */
    private $query;

    /**
     * @var boolean
     */
    private $isCallback = false;

    /**
     * @param Request $request
     */
    public function handleRequest(Request $request)
    {
        $queryData = $request->query->all();

        if ($request->isXmlHttpRequest() && $request->isMethod('GET') && isset($queryData['draw'])) {
            $this->isCallback = true;
            if ($this->toolbar) {
                $this->toolbar->handleRequest($request);
                $this->query = array_merge($queryData, $this->toolbar->getData());
            } else {
                $this->query = $queryData;
            }
        }
    }

    /**
     * @return bool
     */
    public function isCallback()
    {
        return $this->isCallback;
    }

    /**
     * @return DataTableResult
     */
    public function getApiResults()
    {
        $result = $this->source->search($this->columns, $this->query);
        $accessor = PropertyAccess::createPropertyAccessor();

        // compute result
        foreach ($result->data as $row) {
            $fetchedRow = array();

            // Add row id data
            $fetchedRow['DT_RowId'] = $accessor->getValue($row, 'id');

            // Add row class data
            if (is_string($this->options['row_class'])) {
                $fetchedRow['DT_RowClass'] = $this->options['row_class'];
            } else if (is_callable($this->options['row_class'])) {
                $fetchedRow['DT_RowClass'] = call_user_func($this->options['row_class'], $row);
            }

            foreach ($this->columns as $column) {
                $fetchedRow[] = $column->render($row);
            }

            $result->computedData[] = $fetchedRow;
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('id', 'table_' . StringUtils::random(12))
            ->setAllowedTypes('id', 'string')

            ->setDefault('data_class', null)
            ->setAllowedTypes('data_class', ['string', 'null'])


            ->setDefault('attr', [
                'class' => 'table table-striped table-centered'
            ])
            ->setAllowedTypes('attr', ['array'])

            ->setDefault('row_class', null)
            ->setAllowedTypes('row_class', ['null', 'array', 'callable'])

            ->setDefault('paging', true)
            ->setAllowedTypes('paging', 'bool')

            ->setDefault('length_change', false)
            ->setAllowedTypes('length_change', 'bool')

            ->setDefault('length_menu', [25, 50, 100])
            ->setAllowedTypes('length_menu', 'array')

            ->setDefault('page_length', 25)
            ->setAllowedTypes('page_length', 'int')

            ->setDefault('fixed_header', false)
            ->setAllowedTypes('fixed_header', 'bool')

            ->setDefault('poll_interval', null)
            ->setAllowedTypes('poll_interval', ['int', 'null'])

            ->setDefault('orderable', true)
            ->setAllowedTypes('orderable', 'bool')

            ->setDefault('dom', "<'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'li><'col-sm-12 col-md-7'p>>")
            ->setAllowedTypes('dom', 'string')

            ->setDefault('template', '@UmbrellaCore/DataTable/datatable.html.twig')
            ->setAllowedTypes('template', 'string');
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options = array())
    {
        $this->options = $options;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->options['template'];
    }

    /**
     * @return string
     */
    public function getDataClass()
    {
        return $this->options['data_class'];
    }

    /**
     * @param TranslatorInterface $translator
     * @return array
     */
    public function getViewOptions(TranslatorInterface $translator)
    {
        $viewOptions = array();
        $viewOptions['datatable'] = $this;
        $viewOptions['id'] = $this->options['id'];
        $viewOptions['attr'] = $this->options['attr'];

        $viewOptions['columns'] = array();
        foreach ($this->columns as $column) {
            $viewOptions['columns'][] = $column->getViewOptions();
        }

        $jsOptions = array();
        $jsOptions['serverSide'] = true;
        $jsOptions['bFilter'] = false;
        $jsOptions['ajax'] = array(
            'url' => $this->loadUrl
        );

        if ($this->options['paging']) {
            $jsOptions['lengthChange'] = $this->options['length_change'];
            $jsOptions['pageLength'] = $this->options['page_length'];
            $jsOptions['lengthMenu'] = $this->options['length_menu'];
        } else {
            $jsOptions['paging'] = false;
        }

        $jsOptions['fixedHeader'] = $this->options['fixed_header'];

        if ($this->relocateUrl) {
            $jsOptions['rowReorder'] = array(
                'update' => false,
                'url' => $this->relocateUrl
            );
        }

        $jsOptions['poll_interval'] = $this->options['poll_interval'];
        $jsOptions['dom'] = $this->options['dom'];
        $jsOptions['ordering'] = $this->options['orderable'];

        // columns options
        $jsOptions['columns'] = array();
        $jsOptions['order'] = array();

        /** @var Column $column */
        foreach ($this->columns as $idx => $column) {

            if ($column->getDefaultOrder()) {
                $jsOptions['order'][] = array(
                    $idx,
                    strtolower($column->getDefaultOrder())
                );
            }

            $jsOptions['columns'][] = $column->getColumnsOptions();
        }

        $translate = function ($key) use ($translator) {
            return $translator->trans('datatable.' . $key, [], 'datatable');
        };

        // translations
        $jsOptions['language'] = array(
            'processing' => $translate('processing'),
            'search' => $translate('search'),
            'lengthMenu' => $translate('lengthMenu'),
            'info' => $translate('info'),
            'infoEmpty' => $translate('infoEmpty'),
            'infoFiltered' => $translate('infoFiltered'),
            'infoPostFix' => $translate('infoPostFix'),
            'loadingRecords' => $translate('loadingRecords'),
            'zeroRecords' => $translate('zeroRecords'),
            'emptyTable' => $translate('emptyTable'),
            'searchPlaceholder' => $translate('searchPlaceholder'),
            'paginate' => array(
                'first' => $translate('paginate.first'),
                'previous' => $translate('paginate.previous'),
                'next' => $translate('paginate.next'),
                'last' => $translate('paginate.last'),
            ),
            'aria' => array(
                'sortAscending' => $translate('aria.sortAscending'),
                'sortDescending' => $translate('aria.sortDescending'),
            )
        );

        $viewOptions['js'] = $jsOptions;

        return $viewOptions;
    }
}

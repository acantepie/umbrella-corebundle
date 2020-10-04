<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 13/05/17
 * Time: 12:46.
 */

namespace Umbrella\CoreBundle\Component\DataTable\Model;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\Options;
use Umbrella\CoreBundle\Component\Column\Column;
use Umbrella\CoreBundle\Component\ComponentView;
use Symfony\Component\HttpFoundation\ParameterBag;
use Umbrella\CoreBundle\Component\Toolbar\Toolbar;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class DataTable.
 */
class DataTable extends AbstractDataTable
{
    /**
     * @var array()
     */
    private $query = [];

    /**
     * @inheritDoc
     */
    public function handleRequestData(array $requestData)
    {
        $request = new Request();
        $request->query = new ParameterBag($requestData);
        $request->setMethod(Request::METHOD_GET);
        $request->headers->set('X-Requested-With', 'XMLHttpRequest'); // consider request as Ajax request
        $this->handleRequest($request);
    }

    /**
     * @inheritdoc
     */
    public function handleRequest(Request $request)
    {
        $queryData = $request->query->all();

        $this->isCallback = $request->isXmlHttpRequest()
            && $request->isMethod('GET')
            && isset($queryData['_dtid'])
            && $queryData['_dtid'] == $this->options['id'];

        if ($this->isCallback) {
            $this->toolbar->handleRequest($request);
            $this->query = [
                'query' => $queryData,
                'form' => $this->toolbar->getFormData()
            ];
        }
    }

    /**
     * @return DataTableResult
     */
    public function getResults() : DataTableResult
    {
        if (!$this->isCallback) {
            throw new \RuntimeException('Unable to retrieve result, datatable is not on callback context');
        }

        return $this->source->search($this->options['data_class'], $this->columns, $this->query);
    }

    /**
     * @inheritdoc
     */
    public function getApiResults()
    {
        $result = $this->getResults();
        $accessor = PropertyAccess::createPropertyAccessorBuilder()
            ->disableExceptionOnInvalidPropertyPath()
            ->getPropertyAccessor();

        $computedData = [];
        $computedIds = [];

        // compute result
        foreach ($result->data as $row) {
            $fetchedRow = [];

            $id = $accessor->getValue($row, $this->options['id_path']);
            $computedIds[] = $id;

            // Add row id data
            $fetchedRow['DT_RowAttr'] = ['data-id' => $id];

            // Add row class
            $fetchedRow['DT_RowClass'] = '';
            if (is_string($this->options['row_class'])) {
                $fetchedRow['DT_RowClass'] = $this->options['row_class'];
            } elseif (is_callable($this->options['row_class'])) {
                $fetchedRow['DT_RowClass'] = call_user_func($this->options['row_class'], $row);
            }

            // Add row treegrid- class
            if ($this->options['tree']) {
                $fetchedRow['DT_RowClass'] .= sprintf('treegrid-%s', $id);

                $parent = $accessor->getValue($row, $this->options['parent_path']);
                if ($parent) {
                    $parentId = $accessor->getValue($parent, $this->options['id_path']);
                    if (in_array($parentId, $computedIds)) {
                        $fetchedRow['DT_RowClass'] .= sprintf(' treegrid-parent-%s', $parentId);
                    }
                }
            }

            // Add column render
            foreach ($this->columns as $column) {
                $fetchedRow[] = $column->render($row);
            }

            $computedData[] = $fetchedRow;
        }

        $result->data = $computedData;

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('id', $this->defaultId)
            ->setAllowedTypes('id', 'string')

            ->setDefault('id_path', 'id')
            ->setAllowedTypes('id_path', 'string')

            ->setDefault('parent_path', 'parent')
            ->setAllowedTypes('parent_path', 'string')

            ->setDefault('id', $this->defaultId)
            ->setAllowedTypes('id', 'string')

            ->setDefault('data_class', null)
            ->setAllowedTypes('data_class', ['string', 'null'])

            ->setDefault('attr', function (Options $options) {
                return [
                    'class' => $options['tree'] ? 'table table-centered' : 'table table-striped table-centered'
                ];
            })
            ->setAllowedTypes('attr', ['array'])

            ->setDefault('row_class', null)
            ->setAllowedTypes('row_class', ['null', 'array', 'callable'])

            ->setDefault('paging', function (Options $options) {
                return !$options['tree'];
            })
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

            ->setDefault('orderable', function (Options $options) {
                return !$options['tree'];
            })
            ->setAllowedTypes('orderable', 'bool')

            ->setDefault('tree', false)
            ->setAllowedTypes('tree', 'bool')

            ->setDefault('tree_column', 0)
            ->setAllowedTypes('tree_column', ['integer'])

            ->setDefault('tree_state', 'expanded')
            ->setAllowedValues('tree_state', ['expanded', 'collapsed'])

            ->setDefault('template', '@UmbrellaCore/DataTable/datatable.html.twig')
            ->setAllowedTypes('template', 'string')

            ->setDefault('dom', "<'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'li><'col-sm-12 col-md-7'p>>")
            ->setAllowedTypes('dom', 'string');

        // fixme
        $toolbar = new Toolbar();
        $toolbar->configureOptions($resolver);
    }

    /**
     * @param  TranslatorInterface $translator
     * @return ComponentView
     */
    public function createView(TranslatorInterface $translator) : ComponentView
    {
        // js options
        $jsOptions = [];
        $jsOptions['tree'] = $this->options['tree'];
        $jsOptions['tree_column'] = $this->options['tree_column'];
        $jsOptions['tree_state'] = $this->options['tree_state'];

        $jsOptions['serverSide'] = true;
        $jsOptions['bFilter'] = false;
        $jsOptions['ajax'] = [
            'url' => $this->loadUrl
        ];
        $jsOptions['ajax_data'] = [
            '_dtid' => $this->options['id']
        ];

        if ($this->options['paging']) {
            $jsOptions['lengthChange'] = $this->options['length_change'];
            $jsOptions['pageLength'] = $this->options['page_length'];
            $jsOptions['lengthMenu'] = $this->options['length_menu'];
        } else {
            $jsOptions['paging'] = false;
        }

        $jsOptions['fixedHeader'] = $this->options['fixed_header'];

        if ($this->relocateUrl) {
            $jsOptions['rowReorder'] = [
                'update' => false,
                'url' => $this->relocateUrl
            ];
        }

        $jsOptions['poll_interval'] = $this->options['poll_interval'];
        $jsOptions['dom'] = $this->options['dom'];
        $jsOptions['ordering'] = $this->options['orderable'];

        // columns options
        $jsOptions['columns'] = [];
        $jsOptions['order'] = [];

        /** @var Column $column */
        foreach ($this->columns as $idx => $column) {
            if ($column->getDefaultOrder()) {
                $jsOptions['order'][] = [
                    $idx,
                    strtolower($column->getDefaultOrder())
                ];
            }

            $jsOptions['columns'][] = $column->getJsOptions();
        }

        $translate = function ($key) use ($translator) {
            return $translator->trans('datatable.' . $key, [], 'datatable');
        };

        // translations
        $jsOptions['language'] = [
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
            'paginate' => [
                'first' => $translate('paginate.first'),
                'previous' => $translate('paginate.previous'),
                'next' => $translate('paginate.next'),
                'last' => $translate('paginate.last'),
            ],
            'aria' => [
                'sortAscending' => $translate('aria.sortAscending'),
                'sortDescending' => $translate('aria.sortDescending'),
            ]
        ];

        // view vars
        $view = new ComponentView();
        $view->template = $this->options['template'];

        $view->vars['id'] = $this->options['id'];
        $view->vars['attr'] = [
            'id' => $this->options['id'],
            'class' => 'umbrella-datatable-container',
            'data-mount' => 'DataTable',
            'data-options' => $jsOptions
        ];

        $view->vars['table_attr'] = $this->options['attr'];

        $view->vars['toolbar'] = $this->toolbar;

        $view->vars['columns'] = [];
        foreach ($this->columns as $column) {
            $view->vars['columns'][] = $column;
        }

        return $view;
    }
}

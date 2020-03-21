<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 14/05/17
 * Time: 18:46.
 */

namespace Umbrella\CoreBundle\Component\DataTable\Twig;

use Symfony\Component\Translation\TranslatorInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Umbrella\CoreBundle\Component\DataTable\Model\Column;
use Umbrella\CoreBundle\Component\DataTable\Model\DataTable;
use Umbrella\CoreBundle\Component\Toolbar\Model\Toolbar;

/**
 * Class DataTableTwigExtension.
 */
class DataTableTwigExtension extends AbstractExtension
{

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * DataTableTwigExtension constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new TwigFunction('render_datatable', array($this, 'render'), array(
                'is_safe' => array('html'),
                'needs_environment' => true,
            )),
            new TwigFunction('datatable_js_options', array($this, 'getJsOptions')),
            new TwigFunction('datatable_translation_options', array($this, 'buildTranslationOptions')),
            new TwigFunction('datatable_language_option', array($this, 'buildTranslationOptions')),
        );
    }

    /**
     * @param Environment $twig
     * @param DataTable         $dataTable
     *
     * @return string
     */
    public function render(Environment $twig, DataTable $dataTable)
    {
        $options = array();
        $options['datatable'] = $dataTable;
        $options['id'] = $dataTable->id;
        $options['columns'] = $dataTable->columns;
        $options['container_class'] = $dataTable->containerClass;
        $options['class'] = $dataTable->class;

        $options['js'] = $this->getJsOptions($dataTable);
        return $twig->render($dataTable->template, $options);
    }


    /**
     * @param DataTable $dataTable
     *
     * @return array
     */
    private function getJsOptions(DataTable $dataTable)
    {
        $options = array();
        $options['serverSide'] = true;
        $options['bFilter'] = false;
        $options['info'] = $dataTable->info;
        $options['ajax'] = array(
            'url' => $dataTable->loadUrl
        );

        if ($dataTable->paging) {
            $options['lengthChange'] = $dataTable->lengthChange;
            $options['pageLength'] = $dataTable->pageLength;
            $options['lengthMenu'] = $dataTable->lengthMenu;
        } else {
            $options['paging'] = false;
        }

        $options['fixedHeader'] = $dataTable->fixedHeader;

        if ($dataTable->relocateUrl) {
            $options['rowReorder'] = array(
                'update' => false,
                'url' => $dataTable->relocateUrl
            );
        }

        if ($dataTable->rowUrl) {
            $options['rowClick'] = array(
                'url' => $dataTable->rowUrl,
                'xhr' => $dataTable->rowXhr,
                'target_blank' => $dataTable->rowTargetBlank,
                'spinner' => $dataTable->rowXhrSpinner
            );
        }

        $options['poll_interval'] = $dataTable->pollInterval;

        $order = array();

        // columns options
        $columnsOptions = array();

        /** @var Column $column */
        foreach ($dataTable->columns as $idx => $column) {
            if ($column->order) {
                $order[] = array($idx, strtolower($column->order));
            }

            $columnsOption = array(
                'orderable' => $column->orderable,
                'className' => $column->class
            );
            $columnsOptions[] = $columnsOption;
        }

        $options['columns'] = $columnsOptions;

        // default column order
        $options['order'] = $order;
        $options['ordering'] = $dataTable->orderable;
        // translations
        $options['language'] = $this->buildTranslationOptions();

        // toolbar
        if ($dataTable->toolbar) {
            $options['toolbarSubmittedOnChange'] = $dataTable->toolbar->submitFrom === Toolbar::SUBMIT_ONCHANGE;
        }

        $options['dom'] = $dataTable->dom;

        return $options;
    }

    /**
     * @return array
     */
    public function buildTranslationOptions()
    {
        return array(
            'processing' => $this->transDt('processing'),
            'search' => $this->transDt('search'),
            'lengthMenu' => $this->transDt('lengthMenu'),
            'info' => $this->transDt('info'),
            'infoEmpty' => $this->transDt('infoEmpty'),
            'infoFiltered' => $this->transDt('infoFiltered'),
            'infoPostFix' => $this->transDt('infoPostFix'),
            'loadingRecords' => $this->transDt('loadingRecords'),
            'zeroRecords' => $this->transDt('zeroRecords'),
            'emptyTable' => $this->transDt('emptyTable'),
            'searchPlaceholder' => $this->transDt('searchPlaceholder'),
            'paginate' => array(
                'first' => $this->transDt('paginate.first'),
                'previous' => $this->transDt('paginate.previous'),
                'next' => $this->transDt('paginate.next'),
                'last' => $this->transDt('paginate.last'),
            ),
            'aria' => array(
                'sortAscending' => $this->transDt('aria.sortAscending'),
                'sortDescending' => $this->transDt('aria.sortDescending'),
            ), );
    }

    /**
     * @param $key
     *
     * @return string
     */
    private function transDt($key)
    {
        return $this->translator->trans('datatable.'.$key, [], 'datatable');
    }
}

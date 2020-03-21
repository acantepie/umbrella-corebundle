<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 03/06/17
 * Time: 13:46
 */
namespace Umbrella\CoreBundle\Component\Tree\Model;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Environment;
use Umbrella\CoreBundle\Component\Toolbar\Model\Toolbar;
use Umbrella\CoreBundle\Model\OptionsAwareInterface;
use Umbrella\CoreBundle\Component\RowAction\UmbrellaRowActionFactory;
use Umbrella\CoreBundle\Component\Tree\Entity\BaseTreeEntity;
use Umbrella\CoreBundle\Utils\ArrayUtils;

/**
 * Class Tree
 */
class Tree implements OptionsAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var UmbrellaRowActionFactory
     */
    private $rowActionFactory;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var string
     */
    public $id;

    // Options

    /**
     * @var array
     */
    public $options;

    /**
     * @var \Closure
     */
    public $actionBuilder;

    /**
     * @var string
     */
    public $class;

    /**
     * @var bool
     */
    public $collapsable;

    /**
     * @var bool
     */
    public $startExpanded;

    /**
     * @var string
     */
    public $entityName;

    /**
     * @var string
     */
    public $entityRootAlias;

    /**
     * @var string
     */
    public $templateRow;

    /**
     * @var string
     */
    public $relocateUrl;

    /**
     * @var string
     */
    public $loadUrl;

    /**
     * @var null|\Closure
     */
    public $queryClosure;

    /**
     * @var null|\Closure
     */
    public $renderer;

    /**
     * @var null|int
     */
    public $maxDepth;

    /**
     * @var bool
     */
    public $draggable;

    /**
     * @var Toolbar
     */
    public $toolbar;

    // Model

    /**
     * @var TreeQuery
     */
    private $query;

    /**
     * @var BaseTreeEntity|null
     */
    private $result = -1;

    /**
     * Tree constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->rowActionFactory = $container->get(UmbrellaRowActionFactory::class);
        $this->query = new TreeQuery($container->get('doctrine.orm.entity_manager'));
        $this->twig = $container->get('twig');
    }

    /**
     * @param $entity
     *
     * @return string
     */
    public function renderRow($entity)
    {
        return $this->twig->render($this->templateRow, array(
            'tree' => $this,
            'entity' => $entity,
            'actions' => $this->getActions($entity)
        ));
    }

    /**
     * @param $entity
     *
     * @return string
     */
    public function render($entity)
    {
        if (is_callable($this->renderer)) {
            return call_user_func($this->renderer, $entity, $this->options);
        }
        return $this->defaultRender($entity);
    }

    /**
     * @param $entity
     *
     * @return string
     */
    public function defaultRender($entity)
    {
        return (string) $entity;
    }
    /**
     * @param $entity
     * @return array|mixed
     */
    protected function getActions($entity)
    {
        if (is_callable($this->actionBuilder)) {
            return call_user_func($this->actionBuilder, $this, $this->rowActionFactory, $entity);
        }
        return array();
    }


    /**
     * @return BaseTreeEntity|null
     */
    public function getResult()
    {
        if ($this->result === -1) {
            $this->query->build($this);
            $this->result = $this->query->getResult();
        }

        return $this->result;
    }

    /**
     * @return array
     */
    public function getApiResults()
    {
        return array(
            'html' => $this->twig->render('@UmbrellaCore/Tree/tree_load.html.twig', array(
                'tree' => $this
            ))
        );
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options = array())
    {
        $this->options = $options;

        $this->id = ArrayUtils::get($options, 'id', 'tree_'.substr(md5(uniqid('', true)), 0, 12));
        $this->class = ArrayUtils::get($options, 'class');
        $this->collapsable = ArrayUtils::get($options, 'collapsable');
        $this->startExpanded = ArrayUtils::get($options, 'start_expanded');
        $this->templateRow = ArrayUtils::get($options, 'template_row');
        $this->entityName = ArrayUtils::get($options, 'entity');
        $this->renderer = ArrayUtils::get($options, 'renderer');
        $this->maxDepth = ArrayUtils::get($options, 'max_depth');
        $this->draggable = ArrayUtils::get($options, 'draggable');

        $this->actionBuilder = ArrayUtils::get($options, 'action_builder'); // deprecated - use builder instead
        $this->entityRootAlias = ArrayUtils::get($options, 'root_alias'); // deprecated - use builder instead
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(array(
            'entity',
        ));

        $resolver->setDefined(array(
            'id',
            'root_alias', // deprecated - use builder instead

            'class',
            'collapsable',
            'start_expanded',
            'template_row',
            'action_builder', // deprecated - use builder instead
            'renderer',
            'max_depth',
            'draggable'
        ));



        $resolver->setAllowedTypes('collapsable', array('bool'));
        $resolver->setAllowedTypes('start_expanded', array('bool'));
        $resolver->setAllowedTypes('action_builder', array('null', 'callable'));
        $resolver->setAllowedTypes('renderer', array('null', 'callable'));
        $resolver->setAllowedTypes('max_depth', array('null', 'int'));
        $resolver->setAllowedTypes('draggable', array('bool'));

        $resolver->setDefault('class', 'umbrella-tree');
        $resolver->setDefault('collapsable', true);
        $resolver->setDefault('start_expanded', true);
        $resolver->setDefault('template_row', 'UmbrellaCoreBundle:Tree:tree_row.html.twig');
        $resolver->setDefault('draggable', true);
    }
}

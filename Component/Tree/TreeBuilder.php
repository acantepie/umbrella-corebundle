<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 22/01/18
 * Time: 21:31
 */

namespace Umbrella\CoreBundle\Component\Tree;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\RouterInterface;
use Umbrella\CoreBundle\Component\Toolbar\ToolbarFactory;
use Umbrella\CoreBundle\Component\Tree\Model\Tree;

/**
 * Class TreeBuilder
 */
class TreeBuilder
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var ToolbarFactory
     */
    private $toolbarFactory;

    /**
     * @var array
     */
    private $options = array();

    /**
     * @var string
     */
    private $loadUrl;

    /**
     * @var string
     */
    private $relocateUrl;

    /**
     * @var \Closure
     */
    private $queryClosure;

    /**
     * @var Tree|null
     *
     */
    private $resolvedTree;

    /**
     * @var string
     */
    private $rootAlias;

    /**
     * @var callable
     */
    private $rowActionBuilder;

    /**
     * @var string
     */
    private $toolbarClass;

    /**
     * @var array
     */
    private $toolbarOptions = array();

    /**
     * TreeBuilder constructor.
     * @param ContainerInterface $container
     * @param array $options
     */
    public function __construct(ContainerInterface $container, array $options = array())
    {
        $this->container = $container;
        $this->router = $container->get('router');
        $this->toolbarFactory = $container->get(ToolbarFactory::class);
        $this->options = $options;
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
     * @param callable $queryClosure
     * @return \Closure
     */
    public function setQuery(callable $queryClosure)
    {
        return $this->queryClosure = $queryClosure;
    }

    /**
     * @param $class
     * @param array $options
     */
    public function setToolbar($class, array $options = array())
    {
        $this->toolbarClass = $class;
        $this->toolbarOptions = $options;
    }

    /**
     * @param $rootAlias
     */
    public function setRootAlias($rootAlias)
    {
        $this->rootAlias = $rootAlias;
    }

    /**
     * @param callable $actionBuilder
     */
    public function setRowActionBuilder(callable $actionBuilder)
    {
        $this->rowActionBuilder = $actionBuilder;
    }

    /**
     * @return Tree
     */
    public function getTree()
    {
        if ($this->resolvedTree === null) {
            $this->resolvedTree = new Tree($this->container);

            $this->resolvedTree->setOptions($this->options);

            $this->resolvedTree->loadUrl = $this->loadUrl;
            $this->resolvedTree->relocateUrl = $this->relocateUrl;
            $this->resolvedTree->queryClosure = $this->queryClosure;

            if ($this->toolbarClass) {
                $this->resolvedTree->toolbar = $this->toolbarFactory->create($this->toolbarClass, $this->toolbarOptions);
            }
            
            if ($this->rootAlias) {
                $this->resolvedTree->entityRootAlias = $this->rootAlias;
            }

            if ($this->rowActionBuilder) {
                $this->resolvedTree->actionBuilder = $this->rowActionBuilder;
            }
        }

        return $this->resolvedTree;
    }
}
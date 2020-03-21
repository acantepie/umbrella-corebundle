<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 03/06/17
 * Time: 14:09
 */
namespace Umbrella\CoreBundle\Component\Tree\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Umbrella\CoreBundle\Component\Tree\Model\Tree;

/**
 * Class TreeTwigExtension
 */
class TreeTwigExtension extends AbstractExtension
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * TreeTwigExtension constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new TwigFunction('render_tree', array($this, 'render'), array(
                'is_safe' => array('html'),
                'needs_environment' => true,
            ))
        );
    }

    /**
     * @param Environment $twig
     * @param Tree $tree
     * @return string
     */
    public function render(Environment $twig, Tree $tree)
    {
        $options = array();
        $options['tree'] = $tree;
        $options['id'] = $tree->id;
        $options['js'] = $this->buildJsOptions($tree);

        return $twig->render('@UmbrellaCore/Tree/tree.html.twig', $options);
    }

    /**
     * @param Tree $tree
     *
     * @return array
     */
    protected function buildJsOptions(Tree $tree)
    {
        $options = array();
        $options['collapsable'] = $tree->collapsable;
        $options['start_expanded'] = $tree->startExpanded;
        $options['load_url'] = $tree->loadUrl;
        $options['relocate_url'] = $tree->relocateUrl;
        $options['max_depth'] = $tree->maxDepth;
        $options['draggable'] = $tree->draggable;
        return $options;
    }

}
